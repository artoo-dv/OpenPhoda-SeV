<?php

// check for minimum PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 7.4.0 !');
}
// include the config
require_once('config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/en.php');

// load the login class
require_once('classes/Login.php');

// create a login object. 
$login = new Login();

require_once('classes/Register.php');


// create a login object. 
$register = new Register();

   if ($login->isexpLoggedIn() == true) {
        include("views/exp.php");
    }   else {
        include("views/register.php");
    }
