<?php
session_start();
include_once("connection.php");
include_once("functions.php");

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $_SESSION['username'] = $username;

    // Prepare statement to check if the username exists
    $stmt = $con->prepare("SELECT user_password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Use plain username here
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        // Get the stored hashed password
        $password_hashed = $row['user_password'];
        if (password_verify($password, $password_hashed)) {
            header("Location: ../app/index.php");
            exit;
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Username doesn't exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h2>LOGIN</h2>
            <div class="input-field">
                <input name="username" id="username" type="text" required>
                <label>Enter your username</label>
            </div>
            <div class="input-field">
                <input name="password" id="password" type="password" required>
                <label>Enter your password</label>
            </div>
            <button type="submit">Log in</button>
            <?php
            if ($error) {
                echo "<p style='color: red;'>$error</p>";
            }
            ?>
            <div class="register">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>
