<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

require 'db.php';

$form_config = array(
    'method' => "POST",
    'submit_button_text' => "Login",
    'fields' => array(
        array(
            'placeholder' => "Username",
            'type' => "text",
            'name' => "username",
            'required' => True
        ),
        array(
            'placeholder' => "Password",
            'type' => "password",
            'name' => "password",
            'required' => True
        )
    )
);

/* login if user exists in database */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('components/global_head.php') ?>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <?php require('components/header.php') ?>
    <div class="login-container">
        <img src="img/dashboard_handyman_logo_500px.png" alt="Dashboard Handyman Logo">
        <?php require('components/form.php') ?>
        <a class="link block" href="register.php">Register</a>
    </div>
</body>
</html>

