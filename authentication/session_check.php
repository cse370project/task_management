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
        if ($result->num_rows === 0) {
            return [FALSE, "Null"]; // Session ID not found in the database so again unknown user
        }else{
        $row = $result->fetch_assoc();
        return [True, $row["user_id"]]; // User ID found in the session table
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
?>