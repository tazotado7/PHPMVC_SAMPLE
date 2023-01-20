<?php
//სესიის და ქუქის არსებობის ხანგრძლივობის დაყენება
ini_set("session.gc_maxlifetime", $timeout); 
ini_set("session.cookie_lifetime", $timeout);

//სესიის დასაწყისი
session_start();
// დეველოპმენტის პროცესში ვაჩვენებს ყველა ერორს რომ გავასწოროთ.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);


//დასაწყისი პარამეტრები
require_once '../app/config/startup.php';
//პარამეტრების ჩატვირთვის შემდეგ იტვირთება ბუთსტრაფი, ანუ სხვადასხვა ფაილები.
require_once '../app/bootstrap.php';

// ძრავის გამოძახება
use libs\core\Core;

new core();
