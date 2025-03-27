<?php
$servername = "localhost";  // Server name (usually localhost for local development)
$username = "tm_admin";         // Your MySQL username
$password = "tmadmin1234";             // Your MySQL password (leave blank for default settings)
$dbname = "task_management"; // Your target database


$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connected successfully";
}

?>