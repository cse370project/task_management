<?php
include("../db_connection.php");
include("session_check.php"); // Include session check file
$conn = db_connection(); // Establish database connection
$user_exist = get_user_existence_and_id(conn: $conn)[0]; // Check if the user is already logged in;

if ($user_exist === True) {
    header(header: "Location: ../home.php"); // Redirect to home page if user is already logged in
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the input data
    $username = trim(string: $_POST['username']);
    $name = trim(string: $_POST['name']);
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    // Validation logic
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } else {
        // Check if the username already exists in the database
        if (username_exist(username: $username, conn: $conn, user_id: 0)) {
            $errors[] = "Username already exists.";
        }
    }

    if (empty($name)) {
        $errors[] = "Name cannot be empty.";
    }

    if (strlen(string: $password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    
    if ($password !== $retype_password) {
        $errors[] = "Passwords do not match.";
    }

    // If there are no validation errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = base64_encode(string: hash(algo: 'sha3-256', data: $password)); // Save hashed password in Base64 format
        $joining_date = date(format: 'Y-m-d');    
        $insertSql = "INSERT INTO user (username, name, type, password_hash, joining_date) VALUES ('$username', '$name', 'guest' , '$hashed_password' , '$joining_date')";
        $result = $conn->query(query: $insertSql);
    }
} 

// Display the registration form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        .form-container h2 {
            margin: 20px 0;
            color: #555555;
        }
    </style>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var checkbox = document.getElementById("show-password");
            passwordField.type = checkbox.checked ? "text" : "password";
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='errors' >$error</p>";
            }
        }
        if (empty($errors) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<p class='success' > New user '$username' created. Try log in </p>";
        }
        ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter Here (username should be unique)" required>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter Here (Full Name)" required>
            
            <label for="password">Password:</label>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="Enter Here (Must be at least 6 characters)" required>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="show-password" onclick="togglePassword()">
                <label for="show-password">Show password</label>
            </div>
            
            <label for="retype_password">Retype Password:</label>
            <input type="password" id="retype_password" name="retype_password" placeholder="Re-enter Password" required>
            
            <button type="submit">Register</button>
        </form>
        <h2 class="center_texts">OR</h2>
        <button onclick="window.location.href='login.php';" >Login</button>
    </div>
</body>
</html>
