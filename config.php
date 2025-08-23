<?php
// Docker MySQL connection
$host = "db";          // Docker service name
$user = "root";        // MySQL root user  
$pass = "root123";     // Password from docker-compose.yml
$db   = "dailyexpense";
$port = 3306;

$mysqli = mysqli_init();

// Connection timeouts
mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
mysqli_options($mysqli, MYSQLI_OPT_READ_TIMEOUT, 10);

// Establish connection with retry logic
$max_retries = 5;
for ($i = 0; $i < $max_retries; $i++) {
    if (mysqli_real_connect($mysqli, $host, $user, $pass, $db, $port)) {
        mysqli_set_charset($mysqli, 'utf8mb4');
        break;
    }
    
    if ($i === $max_retries - 1) {
        die("Connection failed after $max_retries attempts: " . mysqli_connect_error());
    }
    
    sleep(2); // Wait before retry
}

// Back-compat for existing code expecting $con
$con = $mysqli;
?>