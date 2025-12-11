<?php
require_once __DIR__ . '/../config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
  header("Location: login.php?error=Invalid email or password");
  exit;
}

// Login successful
session_start();
$_SESSION['user_id'] = $user['id'];
header("Location: dashboard.php?message=Login successful");
exit;