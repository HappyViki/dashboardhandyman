<?php
session_start();
require 'db.php';

$form_config = array(
    'method' => "POST",
    'submit_button_text' => "Register",
    'fields' => array(
        array(
            'placeholder' => "Username",
            'type' => "text",
            'name' => "username",
            'required' => True
        ),
        array(
            'placeholder' => "Email",
            'type' => "email",
            'name' => "email",
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        header("Location: index.php");
    } catch (PDOException $e) {
        echo "Registration failed: " . $e->getMessage();
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
        <a class="link block" href="/">Login</a>
    </div>
</body>
</html>


