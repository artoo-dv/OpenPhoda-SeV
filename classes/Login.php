<?php

/**
 * handles the user login/logout/session
 * Modified from Login.php released by
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
  */
class Login
{
    
    private $db_connection = null;
    
    private $exp_id = null;
    
    private $exp_name = "";
    
    private $exp_is_logged_in = false;

    private $step2_passed = false;

    public $errors = array();

    public $messages = array();

    /**
     * @var string|null message to alert the user with on the login page (e.g. bad credentials)
     */
    public $login_error = null;


    public function __construct()
    {
        // create/read session
        session_start();

       if (isset($_GET["logout"])) {
            $this->doLogout();
        
        // login with cookie
        } elseif (isset($_COOKIE['hedgehog'])) {
             $this->loginWithCookieData();

        } elseif (isset($_POST["exp_name"])) {
            $this->loginWithPostData($_POST['exp_name'], $_POST['exp_password']);
        
        } elseif ($this->isexpLoggedIn() == true){
            //do nothing
        } else { 
            $_SESSION = array();
                session_destroy();}
    }

    /**
     * Checks if database connection is opened. If not, then this method tries to open it.
     * @return bool Success status of the database connecting process
     */
    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                // Generate a database connection, using the PDO connector
                // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
                // Also important: We include the charset, as leaving it out seems to be a security issue:
                // @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Connecting_to_MySQL says:
                // "Adding the charset to the DSN is very important for security reasons,
                // most examples you'll see around leave it out. MAKE SURE TO INCLUDE THE CHARSET!"
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        // default return
        return false;
    }

   
    /**
     * Search into database for the exp data of exp_name specified as parameter
     * @return exp data as an object if existing exp
     * @return false if exp_name is not found in the database
     * TODO: @devplanete This returns two different types. Maybe this is valid, but it feels bad. We should rework this.
     * TODO: @devplanete After some resarch I'm VERY sure that this is not good coding style! Please fix this.
     */
    private function getexpData($exp_name)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected exp
            $query_exp = $this->db_connection->prepare('SELECT * FROM experiments WHERE exp_name = :exp_name');
            $query_exp->bindValue(':exp_name', $exp_name, PDO::PARAM_STR);
            $query_exp->execute();
            #$query_exp->debugDumpParams();
            #print_r($query_exp->fetchObject());
            // get result row (as an object)
            return $query_exp->fetchObject();
        } else {
            return false;
        }
    }

    /**
     * Logs in via the Cookie
     * @return bool success state of cookie login
     */
    private function loginWithCookieData()
    {
        if (isset($_COOKIE['hedgehog'])) {
            // extract data from the cookie
            list ($exp_id, $token, $hash) = explode(':', $_COOKIE['hedgehog']);
            // check cookie hash validity
            if ($hash == hash('sha256', $exp_id . ':' . $token . COOKIE_SECRET_KEY) && !empty($token)) {
                // cookie looks good, try to select corresponding exp
                if ($this->databaseConnection()) {
                    // get real token from database (and all other data)
                    $sth = $this->db_connection->prepare("SELECT id, exp_name FROM experiments WHERE id = :id
                                                      AND exp_rememberme_token = :exp_rememberme_token AND exp_rememberme_token IS NOT NULL");
                    $sth->bindValue(':id', $exp_id, PDO::PARAM_INT);
                    $sth->bindValue(':exp_rememberme_token', $token, PDO::PARAM_STR);
                    $sth->execute();
                    // get result row (as an object)
                    $result_row = $sth->fetchObject();
                    if (isset($result_row->id)) {
                        // write exp data into PHP SESSION [a file on your server]
                        $_SESSION['exp_id'] = $result_row->id;
                        $_SESSION['exp_name'] = $result_row->exp_name;
                        $_SESSION['exp_logged_in'] = 1;
                        // declare exp id, maintein the login and step2 tatus to true
                        $this->exp_id = $result_row->id;
                        $this->exp_name = $result_row->exp_name;
                        $this->exp_is_logged_in = true;
                        $this->step2_passed = true;

                        // Cookie token usable only once
                        $this->newRememberMeCookie();
                        return true;
                    }
                }
            }
            // A cookie has been used but is not valid... we delete it
            $this->deleteRememberMeCookie();
            $this->errors[] = MESSAGE_COOKIE_INVALID;
        }
        return false;
    }

    
    private function loginWithPostData($exp_name, $exp_password)
    {
        if (empty($exp_name)) {
            $this->errors[] = MESSAGE_EXPERIMENT_EMPTY;
        } else if (empty($exp_password)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;

        // if POST data (from login form) contains non-empty exp_name and non-empty exp_password
        } else {

            // database query, getting all the info of the selected experiment
            $result_row = $this->getexpData(trim($exp_name));
            // if this exp does not exists
            if (! isset($result_row->id)) {

                // was MESSAGE_exp_DOES_NOT_EXIST before, but has changed to MESSAGE_LOGIN_FAILED
                // to prevent potential attackers showing if the exp exists
                #'$this->errors[] = MESSAGE_LOGIN_FAILED;
                // hand the message to the view instead of echoing it here directly - this used
                // to print raw <script> HTML before the page's <!DOCTYPE>/<head> were even output
                // (Login's constructor runs before any view is included), so it rendered unstyled
                // and reflected $exp_name unescaped into an inline script (XSS)
                $this->login_error = "login failed with experiment id: " . $exp_name . ". \nThis experiment name may not be registered";

            } else if (! password_verify($exp_password, $result_row->exp_password_hash)) {
                $this->login_error = "password not accepted for experiment id: " . $exp_name;

            } else {

                $_SESSION['exp_id'] = $result_row->id;
                $_SESSION['exp_name'] = $result_row->exp_name;
                $_SESSION['exp_logged_in'] = 1;
                $this->exp_is_logged_in = true;
                $this->newRememberMeCookie();
              header("location: index.php");

            }
             
        }
    }

    /**
     * Create all data needed for remember me cookie connection on client and server side
     */
    private function newRememberMeCookie()
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // generate 64 char random string and store it in current exp data
            $random_token_string = hash('sha256', mt_rand());
            $sth = $this->db_connection->prepare("UPDATE experiments SET exp_rememberme_token = :exp_rememberme_token WHERE id = :exp_id LIMIT 1");
            $sth->execute(array(':exp_rememberme_token' => $random_token_string, ':exp_id' => $_SESSION['exp_id']));

            // generate cookie string that consists of expid, randomstring and combined hash of both
            $cookie_string_first_part = $_SESSION['exp_id'] . ':' . $random_token_string;
            $cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
            $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

            // set cookie
            setcookie('hedgehog', $cookie_string, time() + COOKIE_RUNTIME, "/", COOKIE_DOMAIN,0,TRUE);
        }
    }

    /**
     * Delete all data needed for remember me cookie connection on client and server side
     */
    private function deleteRememberMeCookie()
    {
    
        // if database connection opened
        if ($this->databaseConnection()) {
            // Reset rememberme token
            $sth = $this->db_connection->prepare("UPDATE experiments SET exp_rememberme_token = NULL WHERE id = :exp_id LIMIT 1");
        $sth->execute(array(':exp_id' => $_SESSION['exp_id']));
        }

        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obivously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('hedgehog', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN,0,TRUE);

    }

    /**
     * Perform the logout, resetting the session
     */
    public function doLogout()
    {
        $this->deleteRememberMeCookie();

        $_SESSION = array();
        session_destroy();

        $this->exp_is_logged_in = false;
        header("location:index.php");
       
    }

    /**
     * Simply return the current state of the exp's login
     * @return bool exp's login status
     */
    public function isexpLoggedIn()
    {
        return $this->exp_is_logged_in;
    }
        
    public function getexpname()
    {
        return $this->exp_name;
    }

    
}
