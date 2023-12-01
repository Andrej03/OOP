<?php

$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

return $mysqli;