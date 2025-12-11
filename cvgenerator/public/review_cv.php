<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user_id'];

// Load CV and user info
$stmt = $pdo->prepare("SELECT cvs.*, users.full_name, users.email AS uemail FROM cvs JOIN users ON users.id = cvs.user_id WHERE users.id = ?");
$stmt->execute([$user_id]);
$cv = $stmt->fetch();
if (!$cv) {
  exit("No CV found.");
}
$cv_id = $cv['id'];

// Load sections
$eduStmt = $pdo->prepare("SELECT * FROM education WHERE cv_id = ?");
$eduStmt->execute([$cv_id]);
$eduList = $eduStmt->fetchAll();

$workStmt = $pdo->prepare("SELECT * FROM work_experience WHERE cv_id = ?");
$workStmt->execute([$cv_id]);
$workList = $workStmt->fetchAll();

$skillsStmt = $pdo->prepare("SELECT * FROM skills WHERE cv_id = ?");
$skillsStmt->execute([$cv_id]);
$skills = $skillsStmt->fetchAll();

$refsStmt = $pdo->prepare("SELECT * FROM referees WHERE cv_id = ?");
$refsStmt->execute([$cv_id]);
$refs = $refsStmt->fetchAll();

// Load selected template
$template = $cv['template_choice'] ?? 'basic';
$template_file = __DIR__ . '/../templates/cv_' . $template . '.php';

if (!file_exists($template_file)) {
  $template_file = __DIR__ . '/../templates/cv_basic.php';
}

// Pass variables to template
$user = ['full_name' => $cv['full_name'], 'email' => $cv['uemail']];
include $template_file;

?>