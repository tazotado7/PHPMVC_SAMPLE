<?php
//Set the maxlifetime of the session
ini_set("session.gc_maxlifetime", $timeout);

//Set the cookie lifetime of the session
ini_set("session.cookie_lifetime", $timeout);

//Start a new session
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


require_once '../app/config/startup.php';
require_once '../app/bootstrap.php';

use libs\core\Core;

new core();
