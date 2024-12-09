<?php
session_start();
include_once("../login_register/connection.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$username = $_SESSION['username'];
$tasks = [];
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: ../login_register/login.php");
        exit();
    }

    if (isset($_POST["operation"])) {
        $operation = $_POST["operation"];
        $task = $_POST["task"] ?? '';

        // Get the user ID
        $stmt = $con->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_id = $result->fetch_assoc()['user_id'];

        if ($operation === "add" && !empty($task)) {
            $stmt = $con->prepare("SELECT task FROM tasks WHERE task = ? AND user_id = ?");
            $stmt->bind_param("si", $task, $user_id);
            $stmt->execute();
            $task_exists = $stmt->get_result()->num_rows > 0;

            if ($task_exists) {
                $error = "Task already exists.";
            } else {
                $stmt = $con->prepare("INSERT INTO tasks (task, user_id) VALUES (?, ?)");
                $stmt->bind_param("si", $task, $user_id);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } elseif ($operation === "del" && !empty($task)) {
            $stmt = $con->prepare("DELETE FROM tasks WHERE task = ? AND user_id = ?");
            $stmt->bind_param("si", $task, $user_id);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Load tasks from the database
if ($username) {
    $stmt = $con->prepare("SELECT task, task_done FROM tasks WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <header class="navbar">
            <div class="navbar-content">
                <h1 class="navbar-brand">Task Manager</h1>
                <form action="" method="POST" class="logout-form">
                    <button type="submit" name="logout" class="logout-button">Logout</button>
                </form>
            </div>
        </header>

        <main class="content-container">
            <section class="main-content">
                <h1 class="welcome-message">Welcome back, <?php echo htmlspecialchars($username); ?>!</h1>
                <?php if ($error): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="" method="POST" class="task-form">
                    <input type="text" name="task" placeholder="Enter a new task" required class="task-input">
                    <button type="submit" name="operation" value="add" class="add-button">Add Task</button>
                </form>

                <div class="white-line"></div>

                <ul class="task-list">
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item">
                            <div class="task-left">
                                <input 
                                    type="checkbox" 
                                    class="task-checkbox" 
                                    <?php echo $task['task_done'] ? 'checked' : ''; ?>>
                                <span 
                                    class="task-text" 
                                    style="<?php echo $task['task_done'] ? 'text-decoration: line-through; color: #1e1c50;' : ''; ?>">
                                    <?php echo htmlspecialchars($task['task']); ?>
                                </span>
                            </div>
                            <form action="" method="POST" class="delete-form">
                                <input type="hidden" name="task" value="<?php echo htmlspecialchars($task['task']); ?>">
                                <button type="submit" name="operation" value="del" class="delete-button">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>
