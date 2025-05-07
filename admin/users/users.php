<?php
include("../../authentication/session_check.php");
include("../../db_connection.php");
$conn = db_connection();

// Verify user authentication and admin status
$user_data = get_user_existence_and_id(conn: $conn);
if (!$user_data[0]) {
    header("Location: ../authentication/login.php");
    exit();
}

$user_id = $user_data[1];
if (user_type(conn: $conn, user_id: $user_id) != "admin") {
    die("<div class='alert alert-danger'>You are not authorized to access this page.</div>");
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $delete_id = (int)$_POST['user_id'];
    if ($delete_id !== $user_id) { // Prevent self-deletion
        if (delete_user($conn, $delete_id)) {
            $message = "<div class='alert alert-success'>User deleted successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error deleting user.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>You cannot delete yourself.</div>";
    }
}

// Functions
function display_all_users($conn) {
    $query = "SELECT user_id, username, name, type, joining_date, email FROM user ORDER BY joining_date DESC";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-striped table-bordered'>";
        echo "<thead class='thead-dark'><tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Type</th>
                <th>Join Date</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
              </tr></thead><tbody>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            $status = is_user_logged_in($conn, $row['user_id']) ? 
                "<span class='badge badge-success'>Online</span>" : 
                "<span class='badge badge-secondary'>Offline</span>";
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['joining_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>$status</td>";
            echo "<td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                        <button type='submit' name='delete_user' class='btn btn-danger btn-sm' 
                                onclick='return confirm(\"Are you sure you want to delete this user?\")'>
                            Delete
                        </button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No users found.</div>";
    }
}

function is_user_logged_in($conn, $user_id) {
    $query = "SELECT 1 FROM session WHERE user_id = ? AND expire_time > NOW() LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

function delete_user($conn, $user_id) {
    $query = "DELETE FROM user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    return mysqli_stmt_execute($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .admin-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-header d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-users-cog"></i> User Management</h1>
            <a href="dashboard.php" class="btn btn-primary">
                <i class="fas fa-tachometer-alt"></i> Admin Dashboard
            </a>
        </div>

        <?php if (isset($message)) echo $message; ?>

        <div class="table-container">
            <div class="d-flex justify-content-between mb-3">
                <h3>All Users</h3>
                <div>
                    <span class="badge badge-success">Online</span>
                    <span class="badge badge-secondary">Offline</span>
                </div>
            </div>
            
            <?php display_all_users($conn); ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>