<?php

include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection();

// checked if user is logged in
$user_exist = get_user_existence_and_id(conn: $conn)[0];
if (!$user_exist){
    header(header: "Location: ../authentication/login.php");
    exit();
}


function get_resource_path($file_path): string {
    $file_path = dirname(path: __DIR__) . '/resources/' . $file_path;
    
    // Check if file exists before returning
    if (file_exists(filename: $file_path)) {
        return $file_path;
    } else {
        return $file_path;
    }
}


// Get the requested file from the URL
$file_path = isset($_GET['file_path']) ? $_GET['file_path'] : '';

if (!$file_path) {
    http_response_code(response_code: 400);
    die("Invalid request.");
}

// Resolve the full path using your function
$fullPath = get_resource_path(file_path: $file_path);

// Check if the file exists and is readable
if (!file_exists(filename: $fullPath) || !is_readable(filename: $fullPath)) {
    http_response_code(response_code: 404);
    die("File not found.");
}

// Determine MIME type
$mime = mime_content_type(filename: $fullPath);
header(header: "Content-Type: " . $mime);
header(header: "Content-Length: " . filesize(filename: $fullPath));

// Serve the file
readfile(filename: $fullPath);
exit;
?>
