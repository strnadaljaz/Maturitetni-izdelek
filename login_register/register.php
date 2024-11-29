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
    $username_exists = $result->num_rows > 0;

    $username_valid = usernameValid($username);
    $password_valid = passwordValid($password, $confirm_password);

    if ($username_exists)
        $error = "Username is taken!";
    elseif ($username_valid != "")
        $error = $username_valid;
    elseif ($password_valid != "")
        $error = $password_valid;
    else {
        // Hash the password
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Enter data into database
        $query = $con->prepare("INSERT INTO users (username, user_password) VALUES (?, ?)");
        $query->bind_param("ss", $username, $password_hashed);
        $query->execute();

        // Throw a message that registration was successful
        echo "<script type='text/javascript'>alert('Registration was successful');</script>";
        
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h2>REGISTER</h2>
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
            <?php
                if ($error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            ?>
            <div class="login">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>

</body>
</html>
