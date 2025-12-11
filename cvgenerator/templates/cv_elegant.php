<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 'Georgia', serif;
      font-size: 12pt;
      padding: 40px;
      color: #2c3e50;
      background: #fff;
      line-height: 1.6;
    }

    h1 {
      font-size: 24pt;
      margin-bottom: 10px;
      border-bottom: 2px solid #2980b9;
      padding-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #2c3e50;
    }

    h2 {
      font-size: 16pt;
      margin-top: 30px;
      margin-bottom: 15px;
      padding: 4px 8px;
      background: #ecf0f1;
      border-left: 4px solid #2980b9;
      text-transform: uppercase;
      color: #2c3e50;
    }

    .section {
      margin-bottom: 25px;
    }

    .item {
      margin-bottom: 18px;
      padding-bottom: 8px;
      border-bottom: 1px solid #ddd;
    }

    .verified {
      color: #27ae60;
      font-weight: bold;
    }

    .pending {
      color: #e67e22;
      font-weight: bold;
    }

    ul {
      padding-left: 20px;
      margin-top: 5px;
    }

    .photo {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #2980b9;
      margin-bottom: 20px;
    }

    .meta p {
      margin: 4px 0;
    }

    .label {
      font-weight: bold;
      color: #34495e;
    }

    .item p {
      margin: 3px 0;
    }
  </style>
</head>
<body>

<!-- PROFILE PHOTO -->
<?php if (!empty($cv['profile_photo'])): ?>
  <div style="text-align:center;">
    <img src="../uploads/profile_photos/<?= htmlspecialchars($cv['profile_photo']) ?>" class="photo" alt="Profile Photo">
  </div>
<?php endif; ?>

<!-- NAME -->
<h1><?= htmlspecialchars($user['full_name']) ?></h1>
<h2>Professional: <?= htmlspecialchars($cv['title']) ?></h2>

<!-- CONTACT DETAILS -->
<div class="meta">
  <p><span class="label">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
  <p><span class="label">Phone:</span> <?= htmlspecialchars($cv['phone']) ?></p>
  <p><span class="label">Website:</span> <?= htmlspecialchars($cv['website']) ?></p>
  <p><span class="label">Address:</span> <?= htmlspecialchars($cv['address']) ?></p>
</div>

<!-- SUMMARY -->
<h2>Professional Summary</h2>
<p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>

<!-- EDUCATION -->
<h2>Education</h2>
<div class="section">
  <?php foreach ($eduList as $e): ?>
    <div class="item">
      <p><span class="label">Institution:</span> <?= htmlspecialchars($e['institution']) ?></p>
      <p><span class="label">Degree:</span> <?= htmlspecialchars($e['degree']) ?></p>
      <p><span class="label">From:</span> <?= htmlspecialchars($e['start_date']) ?> to <?= htmlspecialchars($e['end_date']) ?></p>
      <p><span class="label">Description:</span><br><?= nl2br(htmlspecialchars($e['description'])) ?></p>
      <p><span class="label">Status:</span>
        <?= $e['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
      </p>
    </div>
  <?php endforeach; ?>
</div>

<!-- WORK EXPERIENCE -->
<h2>Work Experience</h2>
<div class="section">
  <?php foreach ($workList as $w): ?>
    <div class="item">
      <p><span class="label">Company:</span> <?= htmlspecialchars($w['company']) ?></p>
      <p><span class="label">Role:</span> <?= htmlspecialchars($w['role']) ?></p>
      <p><span class="label">Period:</span> <?= htmlspecialchars($w['start_date']) ?> to <?= htmlspecialchars($w['end_date']) ?></p>
      <p><span class="label">Status:</span>
        <?= $w['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
      </p>
      <p><span class="label">Responsibilities:</span><br><?= nl2br(htmlspecialchars($w['responsibilities'])) ?></p>
    </div>
  <?php endforeach; ?>
</div>

<!-- SKILLS -->
<h2>Skills</h2>
<ul>
  <?php foreach ($skills as $s): ?>
    <li><?= htmlspecialchars($s['skill_name']) ?> (<?= htmlspecialchars($s['level']) ?>)</li>
  <?php endforeach; ?>
</ul>

<!-- REFEREES -->
<h2>Referees</h2>
<div class="section">
  <?php foreach ($refs as $r): ?>
    <div class="item">
      <p><strong><?= htmlspecialchars($r['name']) ?></strong></p>
      <p><span class="label">Relation:</span> <?= htmlspecialchars($r['relation']) ?></p>
      <p><span class="label">Email:</span> <?= htmlspecialchars($r['email']) ?></p>
      <p><span class="label">Status:</span>
        <?= $r['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
      </p>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
