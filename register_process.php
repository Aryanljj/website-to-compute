<?php
require_once 'config.php';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$invite_code = $_POST['invite_code'] ?? '';

if($password !== $confirm) header("Location: register.php?error=Password mismatch");
if(strlen($password)<8 || !preg_match('/[a-zA-Z]/',$password) || !preg_match('/[0-9]/',$password)) 
    header("Location: register.php?error=Password must be 8+ chars with letters and numbers");

$stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
$stmt->execute([$phone]);
if($stmt->fetch()) header("Location: register.php?error=Phone already registered");

$user_invite_code = substr(md5(uniqid()),0,8);
$referrer_id = null;
if($invite_code){
    $stmt = $pdo->prepare("SELECT id FROM users WHERE invite_code = ?");
    $stmt->execute([$invite_code]);
    $ref = $stmt->fetch();
    if($ref) $referrer_id = $ref['id'];
}
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (phone,email,password,invite_code,referred_by,balance) VALUES (?,?,?,?,?,100)");
$stmt->execute([$phone,$email,$hashed,$user_invite_code,$referrer_id]);
if($referrer_id){
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + 50 WHERE id = ?");
    $stmt->execute([$referrer_id]);
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status) VALUES (?, 'bonus', 50, 'approved')");
    $stmt->execute([$referrer_id]);
}
header("Location: register.php?success=1");
?>
