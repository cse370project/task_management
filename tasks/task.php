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
    <title>Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="date"],
        form textarea,
        form select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #b3e5fc;
            border-radius: 10px;
            background: #e1f5fe;
        }

        form button {
            background: #039be5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        form button:hover {
            background: #0288d1;
        }

        .filter-buttons button {
            margin-right: 5px;
            margin-bottom: 10px;
            padding: 10px;
            background: #039be5;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .filter-buttons button:hover {
            background: #0288d1;
        }

        .tasks-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-around;
        }

        .task-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 300px;
            margin-bottom: 20px;
        }

        .task-card h3 {
            color: #0277bd;
            margin-bottom: 10px;
        }

        .task-card p {
            color: #333;
            margin-bottom: 10px;
        }

        .task-card .status {
            padding: 5px 10px;
            border-radius: 5px;
        }

        .task-card .ToDo {
            background-color: #ffeb3b;
        }

        .task-card .InProgress {
            background-color: #ffa726;
        }

        .task-card .Done {
            background-color: #66bb6a;
        }

        @media (min-width: 600px) {
            form {
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }

            .task-card {
                width: 45%;
            }
        }

        @media (min-width: 1000px) {
            .task-card {
                width: 30%;
            }
        }
    </style>
</head>

<body>

    <h2>Add Task</h2>
    <form action="add_task.php" method="POST">
        <input type="text" name="taskTitle" placeholder="Title" required>
        <textarea name="taskDetail" placeholder="Detail" required></textarea>
        <input type="date" name="date" required>
        <select name="status" required>
            <option value="ToDo">ToDo</option>
            <option value="InProgress">InProgress</option>
            <option value="Done">Done</option>
        </select>
        <button type="submit">Add Task</button>
    </form>

    <h2>Search & Filter</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Search by title" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <form method="GET" class="filter-buttons">
        <button type="submit" name="filter" value="">All</button>
        <button type="submit" name="filter" value="ToDo">ToDo</button>
        <button type="submit" name="filter" value="InProgress">InProgress</button>
        <button type="submit" name="filter" value="Done">Done</button>
    </form>

    <h2>All Tasks</h2>
    <div class="tasks-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="task-card">
                <h3><?= htmlspecialchars($row['taskTitle']) ?></h3>
                <p><?= htmlspecialchars($row['taskDetail']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>
                <p class="status <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>
