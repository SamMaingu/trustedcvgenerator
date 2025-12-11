<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user_id'];
$cvStmt = $pdo->prepare("SELECT id FROM cvs WHERE user_id=?");
$cvStmt->execute([$user_id]);
$cv = $cvStmt->fetch();

if (!$cv) {
  header('Location: dashboard.php?error=nocv');
  exit;
}

$cv_id = $cv['id'];

if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
  header('Location: dashboard.php?error=upload');
  exit;
}

$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
  header('Location: dashboard.php?error=invalid');
  exit;
}

$filename = 'cv_' . $cv_id . '_' . time() . '.' . $ext;
$target = __DIR__ . '/../uploads/profile_photos/' . $filename;

if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {
  header('Location: dashboard.php?error=movefail');
  exit;
}

$update = $pdo->prepare("UPDATE cvs SET profile_photo=? WHERE id=?");
$update->execute([$filename, $cv_id]);

header('Location: dashboard.php?success=photo');
exit;