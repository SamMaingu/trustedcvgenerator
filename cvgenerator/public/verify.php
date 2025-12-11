<?php
require_once __DIR__ . '/../config/db.php';

$token = $_GET['t'] ?? '';
if (!$token) {
  exit('Missing token');
}

// Load verification record and CV owner's name
$stmt = $pdo->prepare("
  SELECT v.*, u.full_name,
         CASE 
           WHEN v.target_type = 'education' THEN e.institution
           WHEN v.target_type = 'work' THEN w.company
           WHEN v.target_type = 'referee' THEN r.name
         END AS target_name
  FROM verifications v
  LEFT JOIN education e ON v.target_type = 'education' AND v.target_id = e.id
  LEFT JOIN work_experience w ON v.target_type = 'work' AND v.target_id = w.id
  LEFT JOIN referees r ON v.target_type = 'referee' AND v.target_id = r.id
  JOIN cvs c ON c.id = v.cv_id
  JOIN users u ON u.id = c.user_id
  WHERE v.token = ?
");
$stmt->execute([$token]);
$ver = $stmt->fetch();

if (!$ver) {
  exit('Invalid token');
}

// Optional: prevent double submission
if ($ver['status'] !== 'pending') {
  exit('This verification has already been processed.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $decision = $_POST['decision'] ?? 'rejected';

  $pdo->prepare("UPDATE verifications SET status=?, responded_at=NOW() WHERE id=?")
      ->execute([$decision, $ver['id']]);

  if ($decision === 'approved') {
    if ($ver['target_type'] === 'education') {
      $pdo->prepare("UPDATE education SET verified_flag=1 WHERE id=?")->execute([$ver['target_id']]);
    } elseif ($ver['target_type'] === 'work') {
      $pdo->prepare("UPDATE work_experience SET verified_flag=1 WHERE id=?")->execute([$ver['target_id']]);
    } elseif ($ver['target_type'] === 'referee') {
      $pdo->prepare("UPDATE referees SET verified_flag=1 WHERE id=?")->execute([$ver['target_id']]);
    }
  }

  echo "<!doctype html><html><head><meta charset='utf-8'><title>Thank You</title></head><body style='font-family:Segoe UI,sans-serif;padding:40px;'><h2>✅ Thank you</h2><p>Status updated to: <strong>$decision</strong>.</p></body></html>";
  exit;
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verification Request</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      color: #333;
      padding: 40px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2c3e50;
      margin-bottom: 10px;
    }
    p {
      margin-bottom: 20px;
    }
    .button-group {
      display: flex;
      gap: 20px;
    }
    button {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .approve {
      background: #2ecc71;
      color: white;
    }
    .reject {
      background: #e74c3c;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Verification Request</h2>
    <p>You are verifying a record submitted by <strong><?= htmlspecialchars($ver['full_name']) ?></strong>.</p>
    <p><strong>Record:</strong> <?= htmlspecialchars($ver['target_type']) ?> — <?= htmlspecialchars($ver['target_name'] ?? '[record not found]') ?></p>
    <form method="post" class="button-group">
      <button name="decision" value="approved" type="submit" class="approve">✅ Approve</button>
      <button name="decision" value="rejected" type="submit" class="reject">❌ Reject</button>
    </form>
  </div>
</body>
</html>