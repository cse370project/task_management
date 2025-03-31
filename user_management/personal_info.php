<?php

include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection(); // Establish database connection

$user_data = get_user_existence_and_id(conn: $conn); // Get user ID from session

if ($user_data[0]){
    $user_id = $user_data[1]; // Extract user ID from the array

    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query(query: $sql);
    $row = $result->fetch_assoc();

    $username = $row["username"];
    $name = $row["name"];
    $joining_date = $row["joining_date"] ;
    $birth_date =$row["birth_date"];
    $phone_number = $row["phone_number"];
    $email = $row["email"];
    $gender = $row["gender"];
    $profession = $row["profession"];

} else {
    header(header: "Location: ../authentication/login.php"); // Redirect to login page if user is not logged in
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>
  <style>
    /* Global Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 20px;
    }
    /* Container for the profile card */
    .container {
      max-width: 600px;
      margin: auto;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    /* Profile Header */
    .profile-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-header h1 {
      margin: 0;
      font-size: 2rem;
      color: #333;
    }
    /* Profile Details */
    .profile-detail {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 8px 12px;
      border-bottom: 1px solid #eee;
    }
    .detail-item:last-child {
      border-bottom: none;
    }
    .detail-label {
      font-weight: 600;
      color: #555;
    }
    .detail-value {
      color: #777;
    }
    /* Edit Button */
    .edit-btn {
      display: block;
      margin: 20px auto 0;
      padding: 10px 20px;
      font-size: 1rem;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .edit-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="profile-header">
      <h1 id="name">User Name</h1>
    </div>
    <div class="profile-detail">
      <div class="detail-item">
        <span class="detail-label">Username:</span>
        <span class="detail-value" id="username"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Joining Date:</span>
        <span class="detail-value" id="joining_date"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Birth Date:</span>
        <span class="detail-value" id="birth_date"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Phone Number:</span>
        <span class="detail-value" id="phone_number"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Email:</span>
        <span class="detail-value" id="email"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Gender:</span>
        <span class="detail-value" id="gender"></span>
      </div>
      <div class="detail-item">
        <span class="detail-label">Profession:</span>
        <span class="detail-value" id="profession"></span>
      </div>
    </div>
    <button class="edit-btn" onclick="editProfile()">Edit Profile</button>
  </div>
  
  <script>
    // Sample user data; replace this with your PHP data using json_encode($row)
    const userData = <?php echo json_encode($row); ?>;

    // Function to check data and return a meaningful message if empty or null
    function getDisplayValue(value) {
      return value && value.trim() !== "" ? value : "Not Provided";
    }

    // Render user data into the DOM
    function renderUserData() {
      document.getElementById('username').textContent = getDisplayValue(userData.username);
      document.getElementById('name').textContent = getDisplayValue(userData.name);
      document.getElementById('joining_date').textContent = getDisplayValue(userData.joining_date);
      document.getElementById('birth_date').textContent = getDisplayValue(userData.birth_date);
      document.getElementById('phone_number').textContent = getDisplayValue(userData.phone_number);
      document.getElementById('email').textContent = getDisplayValue(userData.email);
      document.getElementById('gender').textContent = getDisplayValue(userData.gender);
      document.getElementById('profession').textContent = getDisplayValue(userData.profession);
    }

    function editProfile() {
      // Logic for editing the profile goes here.
      // This could involve redirecting to an edit form or opening a modal.
      alert("Edit profile feature coming soon!");
    }

    // Initial rendering of user data
    renderUserData();
  </script>
</body>
</html>
