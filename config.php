<?php
// Docker MySQL connection - use service name for inter-container communication
$host = "db";          // Docker service name for database
$user = "root";        // MySQL root user
$pass = "root123";     // Password from docker-compose.yml
$db   = "dailyexpense";
$port = 3306;          // MySQL default port

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