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
$current_user_id = $user_id;
$current_user_role = "non-member";
$group_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

?>




<?php




// Fetch leader info
$stmt_leader = $conn->prepare("
    SELECT u.user_id, u.name
    FROM groups g
    JOIN created_group cg ON g.group_id = cg.group_id
    JOIN member m ON cg.membership_id = m.membership_id
    JOIN user u ON cg.user_id = u.user_id
    WHERE g.group_id = ?
    AND m.type = 'leader';
");
if ($stmt_leader) {
    $stmt_leader->bind_param("i", $group_id);
    $stmt_leader->execute();
    $result_leader = $stmt_leader->get_result();
    if ($result_leader->num_rows > 0) {
        $leader_info = $result_leader->fetch_assoc();
    }
    $stmt_leader->close();
    // No need to handle case where leader is not found, as it should always exist if group exists
} else {
     error_log("Error preparing leader info statement: " . $conn->error);
     // Continue without leader info, maybe show 'Unknown'
}
if ($leader_info['user_id'] == $current_user_id) {
    $current_user_role = 'leader'; // User is the leader of this group
} else {
    // Check if user is a general member
    $stmt_check_member = $conn->prepare("SELECT membership_id FROM joined_group WHERE user_id = ? AND group_id = ?");
    if ($stmt_check_member) {
        $stmt_check_member->bind_param("ii", $current_user_id, $group_id);
        $stmt_check_member->execute();
        $res_check_member = $stmt_check_member->get_result();
        if ($res_check_member->num_rows > 0) {
            $current_user_role = 'general'; // User is a general member
        }
        $stmt_check_member->close();
    } else {
         error_log("Error preparing check member statement: " . $conn->error);
    }
}

if ($current_user_role == 'non-member') {
   echo " You are not authorized to edit this group.";
    exit();
}

// membership varification is done
// next to this will be task list and other things
?>

<?php


$sql = "SELECT membership_id 
FROM created_group 
WHERE user_id = '$user_id' AND group_id = '$group_id' 
UNION 
SELECT membership_id 
FROM joined_group 
WHERE user_id = '$user_id' AND group_id = '$group_id'";

$result = $conn->query($sql);

$membership_id = $result->fetch_assoc()['membership_id'];

?>

<button onclick="window.location.href = 'create_group_task.php?id=<?php echo $group_id ?>'" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add Task</button>
