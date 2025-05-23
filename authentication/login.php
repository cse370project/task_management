
<?php
include("../db_connection.php");
include("session_check.php"); // Include session check file
$conn = db_connection(); // Establish database connection
$user_exist = get_user_existence_and_id(conn: $conn)[0]; // Check if the user is alreaady logged in;

if ($user_exist === True) {
    header(header: "Location: ../home.php"); // Redirect to home page if user is already logged in
    exit();
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
} else {
    header(header: "Location: ./login.php");
    exit();
}

$user_agent =$_SERVER['HTTP_USER_AGENT'];
$hostname = gethostbyaddr($ip_address);
$device_login_info = "User_agent:-$user_agent|IP:-$ip_address|Device Name:-$hostname";


if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    // Get the input data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM user WHERE username = '$username' AND password_hash = '" . base64_encode(string: hash(algo: 'sha3-256', data: $password)) . "'";
    $result = $conn->query(query: $sql);
    

    if ( $result->num_rows > 0) {
        // User exists, generate a session ID
        $session_id = generateSessionKey();
        $sql = "SELECT 1 FROM session WHERE session_id = '$session_id'";
        $temp_result = $conn->query(query: $sql);
        
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        // checking if the randomly genrated session id matches with existing session id
        while ($temp_result->num_rows > 0) {
            $session_id = generateSessionKey();
            $sql = "SELECT 1 FROM session WHERE session_id = '$session_id'";
            $temp_result = $conn->query(query: $sql);
        }
        

        // expire time of a session
        $dtime = date(format: 'Y-m-d H:i:s', timestamp: time() + 86400); // MySQL-compatible DATETIME for 1 day

        // storing the session code in the database
        $sql = "INSERT INTO session ( user_id, session_id, expire_time, device_login_info ) values ( '$user_id', '$session_id', '$dtime', '$device_login_info')";
        $temp_result1 = $conn->query(query: $sql);
        
        if ($temp_result){
            // done storing the session id in the database

            // Set the session cookie
            set_cookie(name: 'session_id', value: $session_id,expire_in_seconds: 86400, path: '/', domain: '', secure: False, httponly: False);
            // Redirect to the home page
            header(header: "Location: ../home.php");
            exit();
        } else {
            echo "<script>alert('Error: Could not log in due to unexpected error. Please try again.');</script>";
        }

    }


}


?>






<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            position: relative;
        }
        .form-container h1 {
            text-align: center;
            color: #333333;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
            color: #555555;
            font-weight: bold;
        }
        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .form-container input[type="text"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: -10px;
            margin-bottom: 15px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .center_texts {
            text-align: center;
            margin: 20px 0;
            color: #555555;
        }
        .errors {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($result->num_rows === 0) {
                echo "<p class='errors'>Invalid username or password.</p>";
            }
        }
        ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <label for="password">Password:</label>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="show-password" onclick="togglePassword()">
                <label for="show-password">Show password</label>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <h2 class="center_texts">OR</h2>
        <button onclick="window.location.href='register.php';">Register</button>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var checkbox = document.getElementById("show-password");
            passwordField.type = checkbox.checked ? "text" : "password";
        }
    </script>
</body>
</html>
