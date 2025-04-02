<?php

include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection();

$user_data = get_user_existence_and_id(conn: $conn);

if ($user_data[0]) {
    $user_id = $user_data[1];

    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = $conn->query(query: $sql);
    $row = $result->fetch_assoc();

    // Convert gender abbreviation to full text
    function getGenderFullName($genderChar): string {
        switch (strtolower($genderChar)) {
            case 'm': return 'Male';
            case 'f': return 'Female';
            case 'o': return 'Other';
            default: return 'Not Provided';
        }
    }
    $row['gender'] = getGenderFullName($row['gender']);

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
    <title>User Profile</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
<div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; position: relative;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <button onclick="window.location.href='../home.php'" style="padding: 8px 16px; font-size: 1rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">Go Back</button>
        <h1 id="name" style="margin: 0; font-size: 2rem; color: #333; text-align: right;">User Name</h1>
    </div>
    <div style="display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Username:</span>
            <span id="username" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Joining Date:</span>
            <span id="joining_date" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Birth Date:</span>
            <span id="birth_date" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Phone Number:</span>
            <span id="phone_number" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Email:</span>
            <span id="email" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px; border-bottom: 1px solid #eee;">
            <span style="font-weight: 600; color: #555;">Gender:</span>
            <span id="gender" style="color: #777;"></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 12px;">
            <span style="font-weight: 600; color: #555;">Profession:</span>
            <span id="profession" style="color: #777;"></span>
        </div>
    </div>
    <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
        <button onclick="window.location.href='security.php'" style="padding: 8px 16px; font-size: 0.9rem; background-color: rgb(199, 37, 37); color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">Security</button>
        <button onclick="window.location.href='edit_profile.php'" style="padding: 8px 16px; font-size: 0.9rem; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">Edit Profile</button>
    </div>
</div>
<script>
    const userData = <?php echo json_encode($row); ?>;
    function getDisplayValue(value) {
        return value && value.trim() !== "" ? value : "Not Provided";
    }
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
    renderUserData();
</script>
</body>
</html>
