<?php
include("authentication/session_check.php");
include("db_connection.php");
$conn = db_connection(); // Establish database connection
$user_data = get_user_id(conn: $conn);
$user_exist = $user_data[0];
if ($user_exist === False) {
    header(header: "Location: authentication/login.php"); // Redirect to login page if user is not logged in
} else {
    $user_id = $user_data[1]; // Get the user ID from the session
    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query(query: $sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $type = $row['type'];

}
function delete_session($conn, $user_id) {
    $sql = "DELETE FROM sessions WHERE user_id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Check if the logout button was clicked
    if (isset($_POST['logout'])) {
        // Delete the session from the database
        delete_session(conn: $conn, user_id: $user_id);
        // Clear the session cookie
        setcookie(name: 'session_id', value: '', expires_or_options: time() - 3600, path: '/', domain: '', secure: true, httponly: true);
        // Redirect to the login page
        header(header: "Location: authentication/login.php");
    } else if (isset($_POST['personal'])) {
        // Redirect to personal page
        header(header: "Location: ./task_manager/tasks.php");
    } else if (isset($_POST['groups'])) {
        // Redirect to groups page
        header(header: "Location: ./colaboration/groups.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* General Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #ffffff; /* White background */
            color: #000000; /* Black text */
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .greeting-box {
            text-align: center;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1.greeting {
            font-size: 2.2em;
            margin: 0 0 20px 0;
        }

        h1.greeting .name {
            font-weight: bold;
        }

        /* Buttons Container */
        .buttons {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        /* Button Styling */
        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .personal-button {
            background-color: #007bff; /* Blue button */
            color: #ffffff;
        }

        .personal-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .group-button {
            background-color: #28a745; /* Green button */
            color: #ffffff;
        }

        .group-button:hover {
            background-color: #1e7e34; /* Darker green on hover */
        }

        /* Logout Button */
        .logout-form {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-button {
            padding: 10px 20px;
            background-color: #ff4553; /* Red button */
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #d13e47; /* Darker red on hover */
        }

        @media (max-width: 768px) {
            h1.greeting {
                font-size: 1.8em;
            }

            .greeting-box {
                padding: 15px 30px;
            }

            .action-button {
                font-size: 0.9em;
            }

            .logout-button {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <!-- Logout Form -->
    <form method="POST" action="home.php" class="logout-form">
        <button type="submit" class="logout-button" name="logout">Logout</button>
    </form>

    <div class="container">
        <div class="greeting-box">
            <?php
            echo "<h1 class='greeting'>Hello, <span class='name'>$name</span></h1>";
            ?>
            <div class="buttons">
                <!-- Personal Button -->
                <form method="POST" action="home.php">
                    <button type="submit" class="action-button personal-button" name="personal">Personal</button>
                </form>

                <!-- Group Button -->
                <form method="POST" action="home.php">
                    <button type="submit" class="action-button group-button" name="groups">Groups</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

