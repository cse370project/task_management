<?php
include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection();

$user_data = get_user_existence_and_id(conn: $conn);
$user_id = $user_data[1];
$errors = array();

if ($user_data[0]){

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $username = trim(string: $_POST['username']);
        $name = trim(string: $_POST['name']);
        $birth_date = trim(string: $_POST['birth_date']);
        $phone_number = trim(string: $_POST['phone_number']);
        $email = trim(string: $_POST['email']);
        $gender = trim(string: $_POST['gender']); // Expecting 'm', 'f', or 'o'
        $profession = trim(string: $_POST['profession']);
    
    
        $sql = "UPDATE user SET username = '$username', name = '$name', birth_date = '$birth_date', phone_number = '$phone_number', email = '$email', gender = '$gender', profession = '$profession' WHERE user_id = '$user_id'";
        $result = $conn->query(query: $sql);
        if ($result){
            header(header: "Location: personal_profile.php");
        } else {
            if (username_exist(username: $username, conn: $conn)) {
                $errors[] = "Username already exists. Try another one.";
            }
            if (email_exist(email: $email, conn: $conn)) {
                $errors[] = "Email already using by another user.";
            }
            if (phone_number_exist(phone_number: $phone_number, conn: $conn)) {
                $errors[] = "Phone number already using by another user.";
            }
            
        }
    
        
    }


    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query(query: $sql);
    $row = $result->fetch_assoc();
} else {
    header(header: "Location: ../authentication/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profile</title>
  <style>
    /* Global Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      position: relative;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    label {
      display: block;
      font-weight: bold;
      margin-top: 10px;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      display: block;
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }
    button:hover {
      background-color: #0056b3;
    }
    .go-back-btn {
      background-color: #6c757d;
    }
    .go-back-btn:hover {
      background-color: #545b62;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Your Profile</h2>
    <?php if (!empty($errors)): ?>
      <div style="color: red; margin-bottom: 20px; text-align: center;">
        <?php foreach ($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form action="edit_profile.php" method="post">

      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars(string: $row['name'] ?? ''); ?>" required>

      <label for="name">Username</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(string: $row['username'] ?? ''); ?>" required>

      <label for="birth_date">Birth Date</label>
      <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars(string: $row['birth_date'] ?? ''); ?>" required>

      <label for="phone_number">Phone Number</label>
      <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars(string: $row['phone_number'] ?? ''); ?>">

      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(string: $row['email'] ?? ''); ?>" required>

      <label for="gender">Gender</label>
      <select id="gender" name="gender">
        <option value="m" <?php echo ($row['gender'] == "m") ? "selected" : ""; ?>>Male</option>
        <option value="f" <?php echo ($row['gender'] == "f") ? "selected" : ""; ?>>Female</option>
        <option value="o" <?php echo ($row['gender'] == "o") ? "selected" : ""; ?>>Other</option>
      </select>

      <label for="profession">Profession</label>
      <input type="text" id="profession" name="profession" value="<?php echo htmlspecialchars($row['profession'] ?? ''); ?>">

      <button type="submit">Update Profile</button>
      <button type="button" class="go-back-btn" onclick="window.location.href='personal_profile.php'">Cancel</button>
    </form>
  </div>

</body>
</html>
