<?php


// Check if the 'session_id' cookie is set

function cookie_exist(): bool{
    if (isset($_COOKIE['session_id'])) {
        return true; // Cookie is set
    } else {
        return false; // Cookie is not set
    }
}
function get_session_from_cookie(): string{
    if (cookie_exist()) {
        return $_COOKIE['session_id']; // Return the session ID from the cookie
    } else {
        return "Null"; // Cookie is not set, return a string Null
    }
}

function get_user_existence_and_id($conn): array{
    $session_id = get_session_from_cookie();
    if ($session_id === "Null"){
        return [False, "Null"]; // Unknown user
    }else{
        
        $sql = "SELECT * FROM session WHERE session_id = '$session_id'";
        $result = $conn->query(query: $sql);
        if ($result->num_rows == 0) {
            return [FALSE, "Null"]; // Session ID not found in the database so again unknown user
        }else{
            $row = $result->fetch_assoc();
            $stored_datetime = $row["expire_time"]; // Expiration date and time from the database
            // Current DateTime
            $current_datetime = date(format: "Y-m-d H:i:s");

            if (strtotime(datetime: $current_datetime) > strtotime(datetime: $stored_datetime)) {
                return [FALSE, "Null"];
            } else {
                return [True, $row["user_id"]]; // User ID found in the session table and the session is not expired
            }
            
        }
    }
    

}    

function set_cookie($name, $value, $expire_in_seconds, $path="/", $domain="", $secure=False, $httponly=False ): void {
    $expires = gmdate(format: 'D, d-M-Y H:i:s T', timestamp: time() + $expire_in_seconds); // Expiration date in GMT
    $domain_header = "Domain=".$domain.";";
    if ($domain === "") {
        $domain_header = ""; // No domain header if domain is empty
    }    
    header(header: "Set-Cookie: $name=$value; expires=$expires; Max-Age=86400; path=".$path.";".$domain_header.($secure ? "secure;" : "").  ($httponly ? "HttpOnly" : ""));

}// a modified version of set cookie function of php. which manually sets the cookie header.
function generateSessionKey($length = 64): string {
    return bin2hex(string: random_bytes(length: $length));
}

function username_exist($username , $conn, $user_id): bool {
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query(query: $sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
        if ($rows["user_id"] == $user_id){
            return false; // username exists but it is the same as the current user
        }else{
            return true; // username exists and it is not the same as the current user
        }
    }else{
        return false; // Username does not exist
    }
}
function email_exist($email , $conn, $user_id): bool {
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query(query: $sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
        if ($rows["user_id"] == $user_id){
            return false; // email exists but it is the same as the current user
        }else{
            return true; // email exists and it is not the same as the current user
        }
    }else{
        return false; // email does not exist
    }
}

function phone_number_exist($phone_number , $conn, $user_id): bool {
    $sql = "SELECT * FROM user WHERE phone_number = '$phone_number'";
    $result = $conn->query(query: $sql);
    if ($result->num_rows > 0) {
        
        $rows = $result->fetch_assoc();
        if ($rows["user_id"] == $user_id){
            return false; // phone_number exists but it is the same as the current user
        }else{
            return true; // phone_number exists and it is not the same as the current user
        }
        
    }else{
        return false; // phone_number does not exist
    }
}

function get_all_sessions($conn, $user_id): ?object {
    // Check if the user ID is valid
    $query = "SELECT * FROM session WHERE user_id = '$user_id'";
    $result = $conn->query(query: $query);
    if ($result->num_rows > 0) {
        return $result; // Return all sessions for the user
    }else{
        return Null; // No sessions found for the user
    }
}

?>