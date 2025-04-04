<?php
include("../authentication/session_check.php");
include("../db_connection.php");
$conn = db_connection(); // Establish database connection

$user_data = get_user_existence_and_id(conn: $conn);
if ($user_data[0]) {
    $user_id = $user_data[1];
} else {
    header(header: "Location: ../authentication/login.php");
    exit();
}

if (!isset($_GET['report_id'])) {
    header(header: "Location: reports.php");
    exit();
}

$report_id = $_GET['report_id'];
$sql = "SELECT * FROM reports WHERE report_id = '$report_id' AND user_id = '$user_id'";
$result = $conn->query(query: $sql);

if (!$result || $result->num_rows === 0) {
    header(header: "Location: reports.php");
    exit();
} else {
    $report = $result->fetch_assoc();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['subject']) && isset($_POST['details'])) {
        $subject = $_POST['subject'];
        $details = $_POST['details'];
        if (empty($subject)){
            $errors[] = 'Subject can not be empty.';
        }
        if (empty($details)){
            $errors[] = 'Details can not be empty.';
        }
        if (strlen($subject) > 100) {
            $errors[] = "Subject must be less than 101 characters.";
        }
        if (strlen($details) > 1000) {
            $errors[] = "Details must be less than 1001 characters.";
        }
        
        $update_sql = "UPDATE reports SET subject = '$subject', details = '$details' WHERE report_id = '$report_id'";
        $conn->query(query: $update_sql);
    }
    
    if (isset($_FILES['report_image'])) {
        $image = $_FILES['report_image'];
        $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $max_size = 10 * 1024 * 1024; // 10MB
        
        if ($image['error'] == UPLOAD_ERR_OK) {
            if ($image['size'] > $max_size) {
                $errors[] = "File size must be 10MB or less.";
            } else {
                $file_type = mime_content_type($image['tmp_name']);
                
                if (!isset($allowed_types[$file_type])) {
                    $errors[] = "Only JPG and PNG files are allowed.";
                } else {
                    $new_extension = '.' . $allowed_types[$file_type];
                    $image_path = "../resources/images/reports/" . $report_id . $new_extension;
                    
                    if (!empty($report['file_extension'])) {
                        $old_image_path = "../resources/images/reports/" . $report_id . $report['file_extension'];
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                    
                    if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                        $errors[] = "Failed to upload the image.";
                    } 
                }
            }
        } else {
            $errors[] = "Error uploading the file.";
        }
    }

    if (empty($errors)){

        if (isset($_FILES['report_image'])) {
            $update_sql = "UPDATE reports SET file_extension = '$new_extension' WHERE report_id = '$report_id'";
            $conn->query($update_sql);
        }
        if (isset($_POST['subject']) && isset($_POST['details'])) {
            
            $update_sql = "UPDATE reports SET subject = '$subject', details = '$details' WHERE report_id = '$report_id'";
            $conn->query(query: $update_sql);
        }
        header(header: "Location: reports.php?success=Report updated successfully.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Report</title>
  <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
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
      input, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
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
    <h2>Edit Report</h2>
    <?php if (!empty($errors)): ?>
      <div style="color: red; margin-bottom: 10px; text-align: center;">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form id="myForm" action="" method="post" enctype="multipart/form-data">
      <label for="subject">Subject</label>
      <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($report['subject']) ?>" required>
      
      <label for="details">Details</label>
      <textarea name="details" id="details" rows="4" required><?= htmlspecialchars($report['details']) ?></textarea>
      
      <label for="report_image">Update Image</label>
      <?php if (empty($report['file_extension'])): ?>
        <p>No image available</p>
      <?php else: ?>
        <img src="../user_management/serve.php?file_path=images/reports/<?= $report_id . $report['file_extension']; ?>" alt="Report Image" style="max-width: 100%; border-radius: 5px;">
      <?php endif; ?>
      <input type="file" name="report_image" accept="image/jpeg, image/png" id="reportImage">
      
      <button type="submit">Update Report</button>
      <button type="button" class="go-back-btn" onclick="window.location.href='reports.php'">Cancel</button>
    </form>
  </div>
</body>

<script>

document.getElementById('myForm').addEventListener('submit', function(event) {
    const fileInput = document.getElementById('reportImage');
    
    // Check if no file is selected
    if (fileInput.files.length === 0) {
        // Remove the file input from the form so it won't be submitted
        fileInput.removeAttribute('name');
    }
  });
</script>
</html>
