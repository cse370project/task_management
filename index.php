<?php
include("db_connection.php");
include("authentication/session_check.php");
$conn = db_connection(); // Establish database connection

$user_exist = get_user_existence_and_id(conn: $conn)[0];

if ($user_exist) {
    header("Location: home.php"); // Redirect to home page if user is logged in

} else {
    header("Location: welcome.html"); // Redirect to login page if user is not logged in
}
?>


