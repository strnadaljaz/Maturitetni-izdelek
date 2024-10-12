<?php
session_start();
include_once("connection.php");
include_once("functions.php"); // Include the functions.php file

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if username exists
    $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Use plain username here
    $stmt->execute();
    $result = $stmt->get_result();
    $usernameExists = $result->num_rows > 0;

    $check_username = checkUsername($username);

    if (!$usernameExists && $password == $confirm_password && $check_username == "") {
        // Hash the password for security
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and execute the query to insert the plain username and hashed password
        $query = $con->prepare("INSERT INTO users (username, user_password) VALUES (?, ?)");
        $query->bind_param("ss", $username, $password_hashed);
        $query->execute();

        header("Location: login.php");
        exit;
    } else {
        if ($usernameExists) {
            $error = "Username is taken!";
        } elseif ($password != $confirm_password) {
            $error = "Passwords do not match.";
        } elseif ($check_username != "") {
            $error = $check_username;
        } else {
            $error = "Please enter some valid information.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="sign_up_style.css">
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h2>REGISTER</h2>
            <?php
            if ($error) {
                echo "<p style='color: red;'>$error</p>";
            }
            ?>
            <div class="input-field">
                <input name="username" id="username" type="text" required>
                <label>Create your username</label>
            </div>
            <div class="input-field">
                <input name="password" id="password" type="password" required>
                <label>Create your password</label>
            </div>
            <div class="input-field">
                <input name="confirm_password" id="confirm_password" type="password" required>
                <label>Confirm your password</label>
            </div>
            <button type="submit">Register</button>
            <div class="login">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>

</body>
</html>
