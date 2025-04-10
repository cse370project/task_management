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
              


        // Ensure username and name are not NULL or empty
        if (empty($username)) {
          $error[] = "Username can not be empty"; // Stop execution if they are empty
        }
        if ( empty($name) ){
          $error[] = "Name can not be empty"; // Stop execution if they are empty;
        }
        if (username_exist(username: $username, conn: $conn, user_id: $user_id)) {
          $errors[] = "Username already exists. Try another one.";
        }
        if (email_exist(email: $email, conn: $conn, user_id: $user_id)) {
            $errors[] = "Email already using by another user.";
        }
        if (phone_number_exist(phone_number: $phone_number, conn: $conn, user_id: $user_id)) {
            $errors[] = "Phone number already using by another user.";
        }
        
        if (empty($errors)) {
          // Convert empty values to NULL for optional fields
          if ($birth_date == "0000-00-00" || empty($birth_date)) {
            $birth_date =   "NULL";
          } else { $birth_date = "'$birth_date'"; }

          if (empty($phone_number)) {
            $phone_number =   "NULL";
          } else { $phone_number = "'$phone_number'"; }

          if (empty($email)) {
            $email =   "NULL";
          } else { $email = "'$email'"; }

          if (empty($gender)) {
            $gender =   "NULL";
          } else { $gender = "'$gender'"; }

          if (empty($profession)) {
            $profession =   "NULL";
          } else { $profession = "'$profession'"; }
      
          $sql = "UPDATE user SET username = '$username', name = '$name', birth_date = $birth_date, phone_number = $phone_number, email = $email, gender = $gender, profession = $profession WHERE user_id = '$user_id'";
          echo $sql;
          $result = $conn->query(query: $sql);
          if ($result){
              header(header: "Location: personal_profile.php");
              exit();
          } else{
          $error[] = "Failed to update profile. Please try again.";
          }
        }
        
    }


    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query(query: $sql);
    $row = $result->fetch_assoc();
} else {
    header(header: "Location: ../authentication/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profile</title>
  <style>

      body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .container {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
      }

      h2 {
        text-align: center;
        color: #333;
      }

      form {
        display: flex;
        flex-direction: column;
      }

      label {
        font-weight: bold;
        margin-top: 10px;
        color: #555;
      }

      input, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
      }

      .input-group {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .clear-btn {
        background: #ff4d4d;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
      }

      .clear-btn:hover {
        background: #cc0000;
      }

      button[type="submit"] {
        background: #28a745;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
      }

      button[type="submit"]:hover {
        background: #218838;
      }

      .go-back-btn {
        background: #007bff;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
      }

      .go-back-btn:hover {
        background: #0056b3;
      }

    
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Your Profile</h2>
    <?php if (!empty($errors)): ?>
      <div style="color: red; margin-bottom: 15px;">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  
    <form action="edit_profile.php" method="post">
      
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? ''); ?>" required>
      
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username'] ?? ''); ?>" required>
      
      <label for="birth_date">Birth Date</label>
      <div class="input-group">
        <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($row['birth_date'] ?? ''); ?>">
        <button type="button" class="clear-btn" onclick="clearField('birth_date')">Clear</button>
      </div>
      
      <label for="phone_number">Phone Number</label>
      <div class="input-group">
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($row['phone_number'] ?? ''); ?>">
        <button type="button" class="clear-btn" onclick="clearField('phone_number')">Clear</button>
      </div>
      
      <label for="email">Email</label>
      <div class="input-group">
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
        <button type="button" class="clear-btn" onclick="clearField('email')">Clear</button>
      </div>
      
      <label for="gender">Gender</label>
      <div class="input-group">
        <select id="gender" name="gender">
          <option value="" <?php echo is_null($row['gender']) ? "selected" : ""; ?>>Not Provided</option>
          <option value="m" <?php echo ($row['gender'] == "m") ? "selected" : ""; ?>>Male</option>
          <option value="f" <?php echo ($row['gender'] == "f") ? "selected" : ""; ?>>Female</option>
          <option value="o" <?php echo ($row['gender'] == "o") ? "selected" : ""; ?>>Other</option>
        </select>
        <button type="button" class="clear-btn" onclick="clearField('gender', true)">Clear</button>
      </div>
      
      <label for="profession">Profession</label>
      <div class="input-group">
        <input type="text" id="profession" name="profession" value="<?php echo htmlspecialchars($row['profession'] ?? ''); ?>">
        <button type="button" class="clear-btn" onclick="clearField('profession')">Clear</button>
      </div>
      
      <button type="submit">Update Profile</button>
      <button type="button" class="go-back-btn" onclick="window.location.href='personal_profile.php'">Cancel</button>
    </form>
  </div>
  
  <script>
    function clearField(fieldId, isSelect = false) {
      const field = document.getElementById(fieldId);
      if (isSelect) {
        field.value = ""; // Reset gender to "Not Provided"
      } else {
        field.value = "";
      }
    }
  </script>
</body>
</html>
