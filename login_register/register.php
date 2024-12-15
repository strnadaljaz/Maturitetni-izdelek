<?php
// PB -> Podatkovna Baza
session_start();
include_once("connection.php"); // Za povezavo s PB
include_once("functions.php"); 

$error = ''; // Za razne napake

// Ob kliku na gumb Submit se izvede spodnja koda
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Pridobim uporabniško ime, geslo in email
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Preverim, če uporabnik s takšnim imenom že obstaja
    $stmt = $con->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Use plain username here
    $stmt->execute();
    $result = $stmt->get_result();
    // Če obstaja se zapiše True, če ne pa False
    $username_exists = $result->num_rows > 0;

    // Enako kot za uporabniško ime naredim za email
    $stmt = $con->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $email_exists = $result->num_rows > 0;

    // Preverim, ali uporabniško ime in geslo zadostujeta določenim pogojem
    // Funkcija za preverjanje tega se nahaja v datoteki functions.php
    $username_valid = usernameValid($username);
    $password_valid = passwordValid($password, $confirm_password);

    // Če uporabniško ime že obstaja
    if ($username_exists)
        $error = "Username is taken!";
    // Če email že obstaja
    else if ($email_exists)
        $error = "Email is taken!";
    // Če uporabniško ime ne zadostuje pogojem
    elseif ($username_valid != "")
        $error = $username_valid;
    // Če geslo ne zadostuje pogojem
    elseif ($password_valid != "")
        $error = $password_valid;
    // Če so vsi pogoji izpolnjeni, novega uporabnika vnesem v bazo
    else {
        // Pred zapisom v PB geslo zgostim oz hashiram
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Vnesemo podatke v PB
        $query = $con->prepare("INSERT INTO users (username, email, user_password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $email, $password_hashed);
        $query->execute();

        // Sporočilo za uprorabnika, da je bila registracija uspešna
        $_SESSION['reg_success'] = True;

        // Preusmerim uporabnika na Login stran
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
    <link rel="stylesheet" href="register_style.css">
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
                <input name="email" id="email" type="email" required>
                <label>Enter your email</label>
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
                // Izpišem napako
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