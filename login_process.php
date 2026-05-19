<?php
require_once 'config.php';
$phone = $_POST['phone'];
$password = $_POST['password'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->execute([$phone]);
$user = $stmt->fetch();
if($user && password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['phone'] = $user['phone'];
    header("Location: dashboard.php");
} else {
    header("Location: login.php?error=Invalid credentials");
}
?>
