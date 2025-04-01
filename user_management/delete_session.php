<?php

include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection(); // Establish database connection
echo"<script>alert('issue found');</script>";
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    
    $user_data = get_user_existence_and_id(conn: $conn);
    
    $user_exist = $user_data[0];
    if ($user_exist) {
        $user_id = $user_data[1];
    }else{
       
        header(header: "Location: ../authentication/login.php");
    }

    // Read the raw POST request body
    $jsonData = file_get_contents(filename: "php://input");

    // Decode the JSON into a PHP array
    $data = json_decode(json: $jsonData, associative: true);

    if (isset($data['delete_session_id'])) {
        $delete_session_id = $data['delete_session_id'];

        $sql = "DELETE FROM session WHERE session_id = '$delete_session_id' AND user_id = '$user_id'";
        $result = $conn->query(query: $sql);
        // Check if the session was deleted successfully
        if ($result && $conn->affected_rows > 0) {
            http_response_code(response_code: 200); // Success
            echo json_encode(value: ["message" => "Session deleted successfully."]);
        } else {
            http_response_code(response_code: 404); // Not Found
            echo json_encode(value: ["message" => "Session not found or failed to delete."]);
        }
    } else {
        http_response_code(response_code: 400); // Bad Request
        echo json_encode(value: ["message" => "Invalid or missing session ID."]);
    }
    
} else {
    echo"<script>alert('issue found');</script>";
    header(header: "Location: ../authentication/login.php");
}

?>