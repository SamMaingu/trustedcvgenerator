<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  exit('Authentication required');
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/utils/tokens.php';

$cv_id = (int)($_POST['cv_id'] ?? 0);

// Load CV owner's name
$userStmt = $pdo->prepare("
  SELECT users.full_name 
  FROM cvs 
  JOIN users ON users.id = cvs.user_id 
  WHERE cvs.id = ?
");
$userStmt->execute([$cv_id]);
$user = $userStmt->fetch();
if (!$user) {
  $user = ['full_name' => 'the applicant'];
}

// Load education entries with verification emails
$eduStmt = $pdo->prepare("SELECT id, institution, degree, verify_email FROM education WHERE cv_id = ? AND verify_email IS NOT NULL AND verify_email != ''");
$eduStmt->execute([$cv_id]);
$eduList = $eduStmt->fetchAll();

// Load work experience entries with verification emails
$workStmt = $pdo->prepare("SELECT id, company, role, verify_email FROM work_experience WHERE cv_id = ? AND verify_email IS NOT NULL AND verify_email != ''");
$workStmt->execute([$cv_id]);
$workList = $workStmt->fetchAll();

// Load referees with emails
$refStmt = $pdo->prepare("SELECT id, name, email FROM referees WHERE cv_id = ? AND email IS NOT NULL AND email != ''");
$refStmt->execute([$cv_id]);
$refList = $refStmt->fetchAll();

// Initialize PHPMailer
$mail = mailer();

// Helper function to send email
function sendVerify($mail, $to, $subject, $body) {
  try {
    $mail->clearAddresses();
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $body;
    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Email to $to failed: " . $e->getMessage());
    return false;
  }
}

$anySent = false; // Track if at least one email was sent

// Send education verification emails
foreach ($eduList as $edu) {
  $token = make_token();
  $stmt = $pdo->prepare("INSERT INTO verifications (cv_id, target_type, target_id, token, status, sent_to) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$cv_id, 'education', $edu['id'], $token, 'pending', $edu['verify_email']]);

  $link = $VERIFY_URL . "?t=$token";
  $body = "Dear Registrar,<br><br>" .
          "Please confirm the study record of <strong>" . htmlspecialchars($user['full_name']) . "</strong>:<br>" .
          "Institution: <strong>{$edu['institution']}</strong><br>" .
          "Degree: <strong>{$edu['degree']}</strong><br><br>" .
          "<a href='$link'>Click here to approve or reject</a>";

  if (sendVerify($mail, $edu['verify_email'], "Education Verification Request", $body)) {
      $anySent = true;
  }
}

// Send work experience verification emails
foreach ($workList as $work) {
  $token = make_token();
  $stmt = $pdo->prepare("INSERT INTO verifications (cv_id, target_type, target_id, token, status, sent_to) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$cv_id, 'work', $work['id'], $token, 'pending', $work['verify_email']]);

  $link = $VERIFY_URL . "?t=$token";
  $body = "Dear HR,<br><br>" .
          "Please confirm the employment record of <strong>" . htmlspecialchars($user['full_name']) . "</strong>:<br>" .
          "Company: <strong>{$work['company']}</strong><br>" .
          "Role: <strong>{$work['role']}</strong><br><br>" .
          "<a href='$link'>Click here to approve or reject</a>";

  if (sendVerify($mail, $work['verify_email'], "Employment Verification Request", $body)) {
      $anySent = true;
  }
}

// Send referee confirmation emails
foreach ($refList as $ref) {
  $token = make_token();
  $stmt = $pdo->prepare("INSERT INTO verifications (cv_id, target_type, target_id, token, status, sent_to) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$cv_id, 'referee', $ref['id'], $token, 'pending', $ref['email']]);

  $link = $VERIFY_URL . "?t=$token";
  $body = "Dear {$ref['name']},<br><br>" .
          "Please confirm you know and can vouch for <strong>" . htmlspecialchars($user['full_name']) . "</strong>.<br><br>" .
          "<a href='$link'>Click here to approve or reject</a>";

  if (sendVerify($mail, $ref['email'], "Referee Confirmation Request", $body)) {
      $anySent = true;
  }
}

// Set session message
if ($anySent) {
    $_SESSION['cv_verify_message'] = "✅ Verification requests sent successfully!";
} else {
    $_SESSION['cv_verify_message'] = "❌ No verification emails could be sent.";
}

header("Location: dashboard.php");
exit;
