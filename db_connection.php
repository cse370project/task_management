<?php
$servername = "localhost";  
$username = "tm_admin";         
$password = "tmadmin1234";            
$dbname = "task_management"; 


$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connected successfully";
}

?>