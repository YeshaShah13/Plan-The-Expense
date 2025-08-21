<?php
$host = "mysql-db";  // Docker service name
$user = "root";
$pass = "rootpassword";
$db   = "dailyexpense";

$con = mysqli_connect($host, $user, $pass, $db);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>