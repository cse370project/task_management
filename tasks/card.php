<!-- <?php
include("../db_connection.php");
$hhh = db_connection();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = "SELECT * FROM tasks WHERE taskTitle LIKE ?";
$params = ["s", "%$search%"];

if ($filter) {
    $sql .= " AND status = ?";
    $params[0] .= "s";
    $params[] = $filter;
}

$sql .= " ORDER BY date DESC";

$stmt = $hhh->prepare($sql);
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task Cards</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0f7fa;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #0277bd;
            margin-top: 40px;
            text-align: center;
            font-weight: bold;
        }

        .task-card {
            background: #ffffff;
            border-left: 5px solid #03a9f4;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .task-card h3 {
            margin: 0;
            color: #01579b;
        }

        .task-status {
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
        }

        .status-todo {
            background-color: #f44336; /* Red */
        }

        .status-inprogress {
            background-color: #ff9800; /* Orange */
        }

        .status-done {
            background-color: #4caf50; /* Green */
        }

        .task-details {
            color: #666;
            margin-top: 10px;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .task-actions button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #ff9800;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-edit:hover {
            background-color: #e68900;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-delete:hover {
            background-color: #e32f2f;
        }

        .btn-comment {
            background-color: #4caf50; /* Green */
            color: white;
            transition: background-color 0.3s;
            padding: 8px 16px;
            display: flex;
            align-items: center;
        }

        .btn-comment:hover {
            background-color: #388e3c;
        }

        .btn-comment i {
            margin-right: 8px;
        }

        @media (min-width: 600px) {
            .task-card {
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>

<body>
    <h2>Task Cards</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($row['taskTitle']) ?></h3>
            <span class="task-status
                <?= ($row['status'] == 'ToDo') ? 'status-todo' : '' ?>
                <?= ($row['status'] == 'InProgress') ? 'status-inprogress' : '' ?>
                <?= ($row['status'] == 'Done') ? 'status-done' : '' ?>">
                <?= htmlspecialchars($row['status']) ?>
            </span>

            <p class="task-details"><?= htmlspecialchars($row['taskDetail']) ?></p>
            <p class="task-details"><strong>Date:</strong> <?= $row['date'] ?></p>

            <div class="task-actions">
                <form action="update_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-edit">Edit</button>
                </form>
                <form action="delete_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this task?')">Delete</button>
                </form>
                <button class="btn-comment">
                    <i class="fas fa-comment"></i> Comment
                </button>
            </div>
        </div>
    <?php endwhile; ?>
</body>

</html> -->

<?php
include("../db_connection.php");
$hhh = db_connection();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = "SELECT * FROM tasks WHERE taskTitle LIKE ?";
$params = ["s", "%$search%"];

if ($filter) {
    $sql .= " AND status = ?";
    $params[0] .= "s";
    $params[] = $filter;
}

$sql .= " ORDER BY date DESC";

$stmt = $hhh->prepare($sql);
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task Cards</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e0f7fa;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #0277bd;
            margin-top: 40px;
            text-align: center;
            font-weight: bold;
        }

        .task-card {
            background: #ffffff;
            border-left: 5px solid #03a9f4;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-card h3 {
            margin: 0;
            color: #01579b;
        }

        .task-status {
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
        }

        .status-todo {
            background-color: #f44336; /* Red */
        }

        .status-inprogress {
            background-color: #ff9800; /* Orange */
        }

        .status-done {
            background-color: #4caf50; /* Green */
        }

        .task-details {
            color: #666;
            margin-top: 10px;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .task-actions button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #ff9800;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-edit:hover {
            background-color: #e68900;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-delete:hover {
            background-color: #e32f2f;
        }

        .btn-comment {
            background-color: #4caf50; /* Green */
            color: white;
            transition: background-color 0.3s;
            padding: 8px 16px;
            display: flex;
            align-items: center;
        }

        .btn-comment:hover {
            background-color: #388e3c;
        }

        .btn-comment i {
            margin-right: 8px;
        }

        @media (min-width: 600px) {
            .task-card {
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>

<body>
    <h2>Task Cards</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="task-card">
            <div class="task-header">
                <h3><?= htmlspecialchars($row['taskTitle']) ?></h3>
                <span class="task-status
                    <?= ($row['status'] == 'ToDo') ? 'status-todo' : '' ?>
                    <?= ($row['status'] == 'InProgress') ? 'status-inprogress' : '' ?>
                    <?= ($row['status'] == 'Done') ? 'status-done' : '' ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </span>
            </div>

            <p class="task-details"><?= htmlspecialchars($row['taskDetail']) ?></p>
            <p class="task-details"><strong>Date:</strong> <?= $row['date'] ?></p>

            <div class="task-actions">
                <form action="update_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-edit">Edit</button>
                </form>
                <form action="delete_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this task?')">Delete</button>
                </form>
                <button class="btn-comment">
                    <i class="fas fa-comment"></i> Comment
                </button>
            </div>
        </div>
    <?php endwhile; ?>
</body>

</html>
