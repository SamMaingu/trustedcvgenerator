<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

$cv_id = (int)($_POST['cv_id'] ?? 0);
$template = $_POST['template_choice'] ?? 'basic';

// Only allow known templates
$allowed = ['basic', 'modern', 'elegant'];
if (!in_array($template, $allowed)) {
  $template = 'basic';
}

// Update the template choice in the database
$update = $pdo->prepare("UPDATE cvs SET template_choice=? WHERE id=?");
$update->execute([$template, $cv_id]);

// Redirect back to dashboard
header('Location: ../public/dashboard.php');
exit;