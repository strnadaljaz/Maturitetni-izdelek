<?php
// PB -> Podatkovna Baza
session_start();
include_once("connection.php"); // Za povezavo s PB
include_once("functions.php");

// Če se je uporabnik ravnokar registriral, izpišem, da je bila registracija uspešna
if (isset($_SESSION['reg_success'])) {
    echo "<script type='text/javascript'>alert('Registration was successful');</script>";
    unset($_SESSION['reg_success']);
}

// Ob kliku na gumb Submit se izvede spodnja koda
$error = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Pridobim uporabniško ime in geslo
    $username = $_POST['username'];
    $password = $_POST['password'];

    $_SESSION['username'] = $username;

    // Preverim, če uporabnik obstaja
    $stmt = $con->prepare("SELECT user_password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Use plain username here
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Spodnja koda se izvede, če uporabnik obstaja
    if ($row) {
        // Pridobim geslo iz PB
        $password_hashed = $row['user_password'];
        // Preverim, ali se vneseno geslo in geslo v PB ujemata
        if (password_verify($password, $password_hashed)) {
            header("Location: ../app/index.php");
            exit;
        // Če se gesli ne ujemata, izpišem opozorilo
        } else {
            $error = "Wrong password!";
        }
    // Če uporabniškega imena ni v PB, izpišem opozorilo
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
            // Izpišem napako
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