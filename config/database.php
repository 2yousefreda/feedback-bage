<?php
define('DB_HOST','localhost');
define('DB_USER','Admin');
define('DB_PASS','123456');
define('DB_NAME','php_dev');

//create connection
$conn=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

//chick connection
if($conn->connect_errno){
    die('connecttion failed' . $conn->connect_error);
}
