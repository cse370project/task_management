<?php
include("../db_connection.php");
include("session_check.php"); // Include session check file
$conn = db_connection(); // Establish database connection
$user_exist = get_user_existence_and_id(conn: $conn)[0]; // Check if the user is alreaady logged in;

if ($user_exist === True) {
    header(header: "Location: ../home.php"); // Redirect to home page if user is already logged in
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the input data
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];

    // Validation logic
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } else {
        // Check if the username already exists in the database
        $sql = "SELECT 1 FROM user WHERE username = '$username'";
        $result = $conn->query(query: $sql);
        if ($result->num_rows > 0) {
            $errors[] = "Username already exists.";
        }
        
    }

    if (empty($name)) {
        $errors[] = "Name cannot be empty.";
    }

    if (strlen(string: $password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // If there are validation errors, display them
    if (empty($errors)) {
       // Validation passed - process the data
        $hashed_password = base64_encode(hash('sha3-256', $password)); // Save hashed password in Base64 format
    
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
        .form-container input[type="text"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
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
            <input type="password" id="password" name="password" placeholder="Enter Here (Must be at least 6 characters)" required>
            
            <button type="submit">Register</button>
        </form>
        <h2 class="center_texts">OR</h2>
        <button onclick="window.location.href='login.php';" >Login</button>
    </div>
</body>
</html>

