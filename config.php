<?php
// Hardened MySQL connection for XAMPP (fixes intermittent "server has gone away")
$host = "127.0.0.1";   // Use IP to avoid DNS/socket issues
$user = "root";        // XAMPP default user
$pass = "";            // XAMPP default password is empty
$db   = "dailyexpense";
$port = 3306;           // Explicit port

$mysqli = mysqli_init();

// Reasonable timeouts (seconds)
mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
mysqli_options($mysqli, MYSQLI_OPT_READ_TIMEOUT, 10);

// Establish connection
if (!mysqli_real_connect($mysqli, $host, $user, $pass, $db, $port)) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure UTF-8
mysqli_set_charset($mysqli, 'utf8mb4');

// Back-compat for existing code expecting $con
$con = $mysqli;

?>