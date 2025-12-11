<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>CV - <?= htmlspecialchars($user['full_name']) ?></title>
  <style>
    /* ---------- Global Styles ---------- */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 12pt;
      padding: 20px;
      color: #2c3e50;
      background-color: #f7f9fc;
      line-height: 1.5;
    }

    h1, h2 {
      margin: 0;
      padding: 0;
    }

    h1 {
      font-size: 22pt;
      color: #34495e;
      border-bottom: 3px solid #3498db;
      padding-bottom: 5px;
      margin-bottom: 10px;
    }

    h1.subtitle {
      font-size: 14pt;
      color: #2980b9;
      border: none;
      margin-bottom: 20px;
    }

    h2 {
      font-size: 14pt;
      color: #2980b9;
      margin-top: 20px;
      margin-bottom: 10px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 3px;
    }

    .section {
      margin-bottom: 20px;
    }

    .item {
      margin-bottom: 10px;
      padding-left: 10px;
    }

    .verified {
      color: #27ae60;
      font-weight: bold;
    }

    .pending {
      color: #e67e22;
      font-weight: bold;
    }

    /* Profile photo */
    .profile-photo {
      text-align: center;
      margin-bottom: 20px;
    }

    .profile-photo img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #3498db;
    }

    /* Contact info */
    .contact-info {
      margin-bottom: 15px;
      padding: 10px;
      background-color: #ecf0f1;
      border-radius: 8px;
    }

    .contact-info strong {
      display: inline-block;
      width: 80px;
      color: #34495e;
    }

    /* Summary */
    .summary {
      padding: 10px;
      background-color: #ffffff;
      border-left: 4px solid #3498db;
      border-radius: 4px;
      margin-bottom: 15px;
    }

    /* Education & Work & Skills & Referees */
    .section .item {
      padding: 10px;
      background-color: #ffffff;
      border-radius: 6px;
      border-left: 4px solid #2980b9;
      margin-bottom: 10px;
    }

    .section .item strong {
      display: block;
      margin-bottom: 3px;
    }

    ol {
      padding-left: 20px;
    }

    /* Responsive adjustments */
    @media print {
      body {
        background-color: #fff;
        padding: 0;
      }
      .contact-info, .summary, .section .item {
        background-color: #fff;
        border: none;
      }
    }

  </style>
</head>
<body>

<?php if (!empty($cv['profile_photo'])): ?>
  <div class="profile-photo">
    <img src="../uploads/profile_photos/<?= htmlspecialchars($cv['profile_photo']) ?>" alt="Profile Photo">
  </div>
<?php endif; ?>

<h1><?= htmlspecialchars($user['full_name']) ?></h1>
<h1 class="subtitle">Professional: <?= htmlspecialchars($cv['title']) ?></h1>

<div class="contact-info">
  <strong>Address:</strong> <?= htmlspecialchars($cv['address']) ?><br>
  <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?><br>
  <strong>Phone:</strong> <?= htmlspecialchars($cv['phone']) ?><br>
  <strong>Website:</strong> <?= htmlspecialchars($cv['website']) ?>
</div>

<div class="summary">
  <h2>Professional Summary</h2>
  <?= nl2br(htmlspecialchars($cv['summary'])) ?>
</div>

<div class="section">
  <h2>Education</h2>
  <?php foreach ($eduList as $e): ?>
    <div class="item">
      <strong><?= htmlspecialchars($e['degree']) ?> - <?= htmlspecialchars($e['institution']) ?></strong>
      From <?= htmlspecialchars($e['start_date']) ?> to <?= htmlspecialchars($e['end_date']) ?><br>
      <?= nl2br(htmlspecialchars($e['description'])) ?><br>
      Status: <?= $e['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
    </div>
  <?php endforeach; ?>
</div>

<div class="section">
  <h2>Work Experience</h2>
  <?php foreach ($workList as $w): ?>
    <div class="item">
      <strong><?= htmlspecialchars($w['role']) ?> at <?= htmlspecialchars($w['company']) ?></strong>
      From <?= htmlspecialchars($w['start_date']) ?> to <?= htmlspecialchars($w['end_date']) ?><br>
      Status: <?= $w['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?><br><br>
      <strong>Responsibilities:</strong>
      <?= nl2br(htmlspecialchars($w['responsibilities'])) ?>
    </div>
  <?php endforeach; ?>
</div>

<div class="section">
  <h2>Skills</h2>
  <ol>
    <?php foreach ($skills as $s): ?>
      <li><?= htmlspecialchars($s['skill_name']) ?> (<?= htmlspecialchars($s['level']) ?>)</li>
    <?php endforeach; ?>
  </ol>
</div>

<div class="section">
  <h2>Referees</h2>
  <?php foreach ($refs as $r): ?>
    <div class="item">
      <strong><?= htmlspecialchars($r['name']) ?></strong><br>
      Email: <?= htmlspecialchars($r['email']) ?> <br>
      Phone: <?= htmlspecialchars($r['phone']) ?> <br>
      Relation: <?= htmlspecialchars($r['relation']) ?><br>
      Status: <?= $r['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
