<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 11pt;
      color: #2c3e50;
      background-color: #f4f6f8;
      margin: 0;
      padding: 30px;
      line-height: 1.5;
    }

    .cv-container {
      max-width: 800px;
      margin: auto;
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .profile-photo {
      display: block;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 20px auto;
      border: 3px solid #3498db;
    }

    h1 {
      text-align: center;
      font-size: 22pt;
      margin-bottom: 5px;
      color: #34495e;
    }

    h2 {
      font-size: 14pt;
      color: #3498db;
      margin-top: 30px;
      margin-bottom: 10px;
      border-bottom: 2px solid #3498db;
      padding-bottom: 3px;
      text-transform: uppercase;
    }

    .meta {
      text-align: center;
      margin-bottom: 20px;
      font-size: 10.5pt;
    }

    .meta span {
      display: inline-block;
      margin: 0 10px;
      font-weight: bold;
      color: #2c3e50;
    }

    .section {
      margin-bottom: 25px;
    }

    .item {
      padding: 12px 15px;
      margin-bottom: 12px;
      background-color: #f9fbfd;
      border-left: 4px solid #3498db;
      border-radius: 6px;
    }

    .item p {
      margin: 3px 0;
    }

    .label {
      font-weight: bold;
      color: #2c3e50;
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
    .contact-info {
    text-align: center;
    margin-bottom: 20px;
    font-size: 11pt;
}

.contact-row {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 5px;
    flex-wrap: wrap;
}

.contact-item {
    min-width: 180px;
}
  </style>
</head>
<body>

<div class="cv-container">

  <!-- Profile Photo -->
  <?php if (!empty($cv['profile_photo'])): ?>
    <img src="../uploads/profile_photos/<?= htmlspecialchars($cv['profile_photo']) ?>" class="profile-photo" alt="Profile Photo">
  <?php endif; ?>

  <!-- Name & Title -->
  <h1><?= htmlspecialchars($user['full_name']) ?></h1>
  <div class="meta">
    <span><?= htmlspecialchars($cv['title']) ?></span>
  </div>

 <!-- Contact Info -->
<div class="contact-info">
  <div class="contact-row">
    <div class="contact-item"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
    <div class="contact-item"><strong>Phone:</strong> <?= htmlspecialchars($cv['phone']) ?></div>
  </div>
  <div class="contact-row">
    <div class="contact-item"><strong>Website:</strong> <?= htmlspecialchars($cv['website']) ?></div>
    <div class="contact-item"><strong>Address:</strong> <?= htmlspecialchars($cv['address']) ?></div>
  </div>
</div>


  <!-- Professional Summary -->
  <h2>Professional Summary</h2>
  <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>

  <!-- Education -->
  <h2>Education</h2>
  <div class="section">
    <?php foreach ($eduList as $e): ?>
      <div class="item">
        <p><span class="label">Institution:</span> <?= htmlspecialchars($e['institution']) ?></p>
        <p><span class="label">Degree:</span> <?= htmlspecialchars($e['degree']) ?></p>
        <p><span class="label">Period:</span> <?= htmlspecialchars($e['start_date']) ?> - <?= htmlspecialchars($e['end_date']) ?></p>
        <p><span class="label">Description:</span> <?= nl2br(htmlspecialchars($e['description'])) ?></p>
        <p><span class="label">Status:</span>
          <?= $e['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Work Experience -->
  <h2>Work Experience</h2>
  <div class="section">
    <?php foreach ($workList as $w): ?>
      <div class="item">
        <p><span class="label">Company:</span> <?= htmlspecialchars($w['company']) ?></p>
        <p><span class="label">Role:</span> <?= htmlspecialchars($w['role']) ?></p>
        <p><span class="label">Period:</span> <?= htmlspecialchars($w['start_date']) ?> - <?= htmlspecialchars($w['end_date']) ?></p>
        <p><span class="label">Responsibilities:</span><br><?= nl2br(htmlspecialchars($w['responsibilities'])) ?></p>
        <p><span class="label">Status:</span>
          <?= $w['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Skills -->
  <h2>Skills</h2>
  <ul>
    <?php foreach ($skills as $s): ?>
      <li><?= htmlspecialchars($s['skill_name']) ?> (<?= htmlspecialchars($s['level']) ?>)</li>
    <?php endforeach; ?>
  </ul>

  <!-- Referees -->
  <h2>Referees</h2>
  <div class="section">
    <?php foreach ($refs as $r): ?>
      <div class="item">
        <p><span class="label">Name:</span> <?= htmlspecialchars($r['name']) ?></p>
        <p><span class="label">Relation:</span> <?= htmlspecialchars($r['relation']) ?></p>
        <p><span class="label">Email:</span> <?= htmlspecialchars($r['email']) ?></p>
        <p><span class="label">Phone:</span> <?= htmlspecialchars($r['phone']) ?></p>
        <p><span class="label">Status:</span>
          <?= $r['verified_flag'] ? '<span class="verified">Verified</span>' : '<span class="pending">Pending</span>' ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>

</div>
</body>
</html>
