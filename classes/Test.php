<?php

/**
 * handles the the main page experiment listing and data export
 * @author artoo
 * @link https://github.com/artoo-git/---
 * @license https://opensource.org/licenses/GPL-3.0 GPLv3 License
 **/
class Test
{
    
    private $db_connection = null;
    
    private $exp_id = null;
    
    private $exp_name = "";
    
    private $exp_is_logged_in = false;
    
    public $errors = array();
    
    public $messages = array();


    public function __construct()
    {
        // opening a new measure in the DB
       if (isset($_POST['NewTest'])){
            $this->newPhoda(
                $_SESSION['exp_id'],
                $_POST['thrpstID'],
                $_POST['patientID'],
                $_POST['session'],
                $_POST['condition']);
        }
        // draw oPhoda interface
        else if (isset($_GET['m'])){
            $this->setupPhoda($_GET['m']);
        }
        // post live data via ajax calls
        else if (isset($_POST['m']) && isset($_POST['item'])){
            
            $this->updatePhoda($_POST['m'], $_POST['item']);
        }
        // abort measure when a specific user POST call is made
        else if(isset($_POST['abort'])) {

            $this->abortPhoda($_POST['abort'], $_SESSION['exp_id']);

        }
        // submit measure when a specific user POST call is made
        else if(isset($_POST['finalise'])) {

            $this->submitPhoda($_POST['finalise'], $_SESSION['exp_id']);

        }
    }

    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        // default return
        return false;
    }

    private function checkUnique($patientID){
        // if database connection opened
        if ($this->databaseConnection()) {

            $query_subj = $this->db_connection->prepare('SELECT * from subjects where patientID = :patientID');
            $query_subj->bindValue(':patientID', $patientID, PDO::PARAM_STR);
            $query_subj->execute();
            $result = $query_subj->fetchObject();
            if (!$result){
                return true;
            }else{
                
                return $result;}
        }
    }

    private function newPhoda($exp_id,$thrpstID,$patientID,$session,$condition)
    {   
        
        if ($this->databaseConnection()) {
            $hash = sha1(time());

            $query_data = $this->db_connection->prepare('INSERT INTO data (
                measure_hash,
                exp_id,
                thrpstID,
                patientID,
                session,
                exp_cond,
                session_date)
                VALUES(
                :measure_hash,
                :exp_id,
                :thrpstID,
                :patientID,
                :session,
                :exp_cond,
                NOW())');
            $query_data->bindValue(':measure_hash', $hash, PDO::PARAM_STR);
            $query_data->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
            $query_data->bindValue(':thrpstID', $thrpstID, PDO::PARAM_STR);
            $query_data->bindValue(':patientID', $patientID, PDO::PARAM_STR);
            $query_data->bindValue(':session', $session, PDO::PARAM_STR);
            $query_data->bindValue(':exp_cond', $condition, PDO::PARAM_STR);
            $query_data->execute();
            //$query_data->debugDumpParams();

            // register the subject the first time they're seen; a patient can have
            // any number of measures/sessions, so an existing subject is not an error
            if ($this->checkUnique($patientID) === true) {
                try {
                    $query_subj = $this->db_connection->prepare('INSERT INTO subjects (
                        patientID,
                        exp_id,
                        reg_date)
                        VALUES(
                        :patientID,
                        :exp_id,
                        NOW())');
                    $query_subj->bindValue(':patientID', $patientID, PDO::PARAM_STR);
                    $query_subj->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
                    $query_subj->execute();
                } catch (PDOException $e) {
                    // a concurrent request registered this subject in the same instant; harmless
                }
            }

            header('location: exp.php?m=' . $hash);
        }
    }

    private function setupPhoda($hash){
        // if database connection opened
        if ($this->databaseConnection()) {

            $query_meas = $this->db_connection->prepare('SELECT * from data where measure_hash = :hash');
            $query_meas->bindValue(':hash', $hash, PDO::PARAM_STR);
            $query_meas->execute();
            $results = $query_meas->fetchObject();
            
            // if there is no line corresponding to the hash requested or if there is hash collision something went wrong: re-enter patient detail
            if (!$results or $query_meas->rowCount() >1){
                ?><script type="text/javascript"> alert("Security token was lost or is invalid, re-enter clients details");
                    window.location.href = "index.php";
                </script><?php
            }else{
                
                
                $results->id[0];
                //echo $result["measure_hash"];
                $i=40;
                $files = scandir('pics/',1);
                $photos = array_diff($files, array('.', '..'));

                ?>

                <!-- TITLE AND CURRENT DATASET PROGRESS -->
                
                    <table style="width: 100%">
                        <tr><td><h1>OpenPhoda-SEV</h1></td>
                            <td > 
                                <table class="phoda">
                                    <tr><?php
                                    // array for phoda table;
                                    $item = 1;
                                    while ($item <= 40) {?>
                                        <td id="col<?php echo $item;?>"></td><?php
                                        $item++;
                                    }
                                    ?></tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                <!-- GREEN - RED surface and 40 cards' deck -->
                <div id=container>
                    <table style="height:100%;"><tr>
                        <td class="rotateLeft"><div>Not Harmful at all</div></td>
                        <td style="width: 100%;text-align: center"></td>
                        <td class="rotateRight"><div>extremely harmful</div></td></tr>
                    </table>

                <form action="exp.php" method="POST" onsubmit="return confirm('Finalise test');">
                        <input type="hidden" name="finalise" value="<?php echo $hash;?>" />
                        <button class="submitBtn" type="submit" value="submit">submit</button>
                </form>
                    
                <!-- the phoda form that is be updated via ajax-->
                    <form method="POST" action="exp.php"><?php
                    foreach ($photos as $photo) {?>
                        <div id="<?php echo $i--;?>"  class="draggable">
                        <img class="image" ondblclick="window.open('pics/<?php echo $photo;?>', 'popup', 'height=400, width=400'); return false;" src="pics/<?php echo $photo;?>">
                        
                        </div>
                        
                        <?php
                    }?>
                    </form>
                </div>

                <!-- Software Licence-->
                <h2 ><a target="_blank" href="https://www.gnu.org/licenses/gpl-3.0.en.html">GPLv3</a></h2>


                <table align="bottom" style="padding: 93vh 10vh 0vh 5vh; width: 100%;">
                    <tr>
                    <td>
                        <form action="exp.php" method="POST" onsubmit="return confirm('This will abort the current test');">
                        <input type="hidden" name="abort" value="<?php echo $hash;?>" />
                        <button class="button" type="submit" value="cancel">cancel</button>
                        </form>
                    </td>
                    
                    <td style="text-align: right;">
                        <form action="exp.php" method="GET">
                        <input type="hidden" name="logout" value = "logout">
                        <button class="button" type="submit" value="logout">logout</button>
                        </form>
                    </td>
                </tr></table><?php    
            }
        }
    }

    private function updatePhoda($hash,$phoda){
        if ($this->databaseConnection()) {

            foreach ($phoda as $key => $value) {
                if (is_numeric($key) && $key >0 && $key<=40 ){
                    $column = "PHODA_" . $key;

                    $query_string = "UPDATE data SET " . $column . " = :value where measure_hash = :hash";
                    $query_ajax = $this->db_connection->prepare($query_string);
                    $query_ajax->bindValue(':hash', $hash, PDO::PARAM_STR);
                    $query_ajax->bindValue(':value', $value, PDO::PARAM_STR);
                    $query_ajax->execute();
                    // error_log($query_data->debugDumpParams());
                }else{
                    error_log("$key value in the POST is not valid");
                }
            }
                
        }
    }

    private function submitPhoda($hash,$exp_id){
        if ($this->databaseConnection()) {

           $query_submit = $this->db_connection->prepare('UPDATE data SET measure_hash = NULL WHERE measure_hash = :hash  AND exp_id = :exp_id LIMIT 1');
            $query_submit->bindValue(':hash', $hash, PDO::PARAM_STR);
            $query_submit->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
            $query_submit->execute();
            //error_log($query_submit->debugDumpParams());
            header('location:index.php');
                
        }
    }

    private function abortPhoda($hash,$exp_id){
        if ($this->databaseConnection()) {

            $query_abort = $this->db_connection->prepare('DELETE FROM data WHERE measure_hash = :hash AND exp_id = :exp_id LIMIT 1');
            $query_abort->bindValue(':hash', $hash, PDO::PARAM_STR);
            $query_abort->bindValue(':exp_id', $exp_id, PDO::PARAM_STR);
            $query_abort->execute();
            header('location:index.php');
                
        }
    }
}