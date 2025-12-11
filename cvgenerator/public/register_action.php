<?php
require_once __DIR__ . '/../config/db.php';

$name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (!$name || !$email || !$password || !$confirmPassword) {
  header("Location: register.php?error=All fields are required");
  exit;
}

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
  // Check for existing email
  $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
  $check->execute([$email]);
  if ($check->fetchColumn() > 0) {
    header("Location: register.php?error=Email already registered");
    exit;
  }

  if ($password !== $confirmPassword) {
    header("Location: register.php?error=Passwords do not match");
    exit;
  }

  // Insert new user
  $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, confirm_password) VALUES (?, ?, ?, ?)");
  $stmt->execute([$name, $email, $hashedPassword, $confirmPassword]);

  // Redirect to login with success message
  header("Location: login.php?message=Account created successfully");
  exit;

} catch (PDOException $e) {
  header("Location: register.php?error=Registration failed");
  exit;
}