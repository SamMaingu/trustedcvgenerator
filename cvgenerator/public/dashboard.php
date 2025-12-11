<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user_id'];

// Get or create CV
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE user_id=?");
$stmt->execute([$user_id]);
$cv = $stmt->fetch();
if (!$cv) {
  $pdo->prepare("INSERT INTO cvs(user_id) VALUES(?)")->execute([$user_id]);
  $cv_id = $pdo->lastInsertId();
  $cv = ['template_choice' => 'basic', 'profile_photo' => ''];
} else {
  $cv_id = $cv['id'];
}

if (!empty($_SESSION['cv_save_message'])): ?>
    <div style="padding:15px;margin-bottom:20px;border-radius:6px;background:#d4edda;color:#155724;">
        <?= $_SESSION['cv_save_message'] ?>
    </div>
<?php 
    unset($_SESSION['cv_save_message']); // remove after showing
endif; 

 if (!empty($_SESSION['cv_verify_message'])): ?>
    <div style="padding:15px;margin-bottom:20px;border-radius:6px;background:#d4edda;color:#155724;">
        <?= $_SESSION['cv_verify_message'] ?>
    </div>
<?php 
    unset($_SESSION['cv_verify_message']);
endif; 



// Load user info
$uStmt = $pdo->prepare("SELECT full_name,email FROM users WHERE id=?");
$uStmt->execute([$user_id]);
$user = $uStmt->fetch();

// Load saved sections for this CV
$eduStmt = $pdo->prepare("SELECT * FROM education WHERE cv_id=? ORDER BY id ASC");
$eduStmt->execute([$cv_id]);
$educations = $eduStmt->fetchAll();

$workStmt = $pdo->prepare("SELECT * FROM work_experience WHERE cv_id=? ORDER BY id ASC");
$workStmt->execute([$cv_id]);
$works = $workStmt->fetchAll();

$skillsStmt = $pdo->prepare("SELECT * FROM skills WHERE cv_id=? ORDER BY id ASC");
$skillsStmt->execute([$cv_id]);
$skills = $skillsStmt->fetchAll();

$refsStmt = $pdo->prepare("SELECT * FROM referees WHERE cv_id=? ORDER BY id ASC");
$refsStmt->execute([$cv_id]);
$refs = $refsStmt->fetchAll();

 ?>



<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CV Builder</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* ---------- GENERAL LAYOUT ---------- */
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f8; /* professional light gray */
        color: #2c3e50;       /* dark slate for text */
        padding: 20px;
        margin: 0;
    }
    .container {
        max-width: 1000px;
        margin: auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* ---------- DASHBOARD HEADER ---------- */
    header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background-color: #ffffff;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    header h1 {
        font-size: 22px;
        color: #34495e; /* dark slate blue */
        margin: 0;
    }
    nav {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }
    nav a.button {
        background-color: #4a90e2; /* professional blue */
        color: #ffffff;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        transition: background 0.3s, transform 0.2s;
    }
    nav a.button:hover {
        background-color: #357ABD;
        transform: translateY(-2px);
    }
    nav a.button.secondary {
        background-color: #ffffff;
        color: #2c3e50;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    nav a.button.secondary:hover {
        background-color: #f0f2f5;
    }

    /* ---------- FORM ---------- */
    .form label {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
        color: #34495e;
    }
    .form input,
    .form textarea,
    .form select {
        width: 100%;
        padding: 10px;
        margin-top: 4px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        background-color: #fafafa;
        transition: border 0.3s, box-shadow 0.3s;
    }
    .form input:focus,
    .form textarea:focus,
    .form select:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 5px rgba(74,144,226,0.3);
        outline: none;
    }

    fieldset {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    legend {
        font-weight: bold;
        color: #34495e;
        padding: 0 10px;
    }

    /* ---------- PROFILE PREVIEW ---------- */
    .profile-preview {
        text-align: center;
        margin-bottom: 25px;
    }
    .profile-preview img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ccc;
    }

    /* ---------- BUTTONS ---------- */
    .button-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 20px;
    }
    button {
      padding: 17px 40px;
      border-radius: 50px;
      cursor: pointer;
      border: 0;
      background-color: #ffffff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-size: 10px;
      transition: all 0.5s ease;
    }
    button.secondary {
      background-color: #ffffff;
      color: #34495e;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    button:hover {
      letter-spacing: 3px;
      background-color: #4a90e2;
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    button:active {
      letter-spacing: 3px;
      background-color: #357ABD;
      color: #ffffff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      transform: translateY(2px);
    }

    .reset-button {
        background-color: #e74c3c;
        color: white;
    }
    .reset-button:hover {
        background-color: #c0392b;
    }

    /* ---------- TABLES ---------- */
    .table th,
    .table td {
        border-bottom: 1px solid #e1e1e1;
        text-align: left;
    }
    .table th {
        background-color: #f7f7f7;
        font-weight: 600;
        color: #2c3e50;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .grid {
            flex-direction: column;
        }
    }
  </style>
</head>
<body>
<header>
  <div>Welcome, <h1><?= htmlspecialchars($user['full_name']) ?></h1></div>
  <nav>
    <form method="post" action="../templates/update_template.php" style="margin:0;">
      <input type="hidden" name="cv_id" value="<?= htmlspecialchars($cv_id) ?>">
      <select name="template_choice" onchange="this.form.submit()" style="padding:10px; border-radius:6px; border:1px solid #4a90e2;">
        <option value="basic" <?= ($cv['template_choice'] ?? '') === 'basic' ? 'selected' : '' ?>>🧾 Basic</option>
        <option value="modern" <?= ($cv['template_choice'] ?? '') === 'modern' ? 'selected' : '' ?>>🖥 Modern</option>
        <option value="elegant" <?= ($cv['template_choice'] ?? '') === 'elegant' ? 'selected' : '' ?>>📜 Elegant</option>
      </select>
    </form>
    <a href="export_pdf.php?cv_id=<?= htmlspecialchars($cv_id) ?>" class="button">📄 Download PDF</a>
    <a href="logout.php" class="button secondary">Logout</a>
  </nav>
</header>

<div class="container">
  <h2>CV Builder</h2>

  <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
    <div style="padding:10px; background:#2ecc71; color:white; border-radius:5px; margin-bottom:15px;">
        ✅ CV reset successfully! You can start fresh now.
    </div>
  <?php endif; ?>

  <?php if (!empty($cv['profile_photo'])): ?>
    <div class="profile-preview">
      <img src="../uploads/profile_photos/<?= htmlspecialchars($cv['profile_photo']) ?>" alt="Profile Photo">
    </div>
  <?php endif; ?>

  <!-- FORM SECTION (PERSONAL, EDUCATION, WORK, SKILLS, REFEREES) -->
  <form method="post" action="save_cv.php" class="form">
    <input type="hidden" name="cv_id" value="<?= htmlspecialchars($cv_id) ?>" autocomplete="off">

    <fieldset><legend>Personal</legend>
      <label>Professional Title<input name="title" value="<?= htmlspecialchars($cv['title'] ?? '') ?>"></label>
      <label>Summary<textarea name="summary"><?= htmlspecialchars($cv['summary'] ?? '') ?></textarea></label>
      <label>Phone<input name="phone" value="<?= htmlspecialchars($cv['phone'] ?? '') ?>"></label>
      <label>Address<input name="address" value="<?= htmlspecialchars($cv['address'] ?? '') ?>"></label>
      <label>Website<input name="website" value="<?= htmlspecialchars($cv['website'] ?? '') ?>"></label>
    </fieldset>

    <fieldset id="edu-set"><legend>Education</legend>
      <div id="edu-list">
        <?php if (!empty($educations)): foreach ($educations as $ed): ?>
          <div class="edu-item">
            <input type="hidden" name="edu_id[]" value="<?= htmlspecialchars($ed['id']) ?>">
            <input name="edu_institution[]" placeholder="Institution" value="<?= htmlspecialchars($ed['institution']) ?>">
            <input name="edu_degree[]" placeholder="Degree" value="<?= htmlspecialchars($ed['degree']) ?>">
            <label>Start<input type="date" name="edu_start[]" value="<?= htmlspecialchars($ed['start_date']) ?>"></label>
            <label>End<input type="date" name="edu_end[]" value="<?= htmlspecialchars($ed['end_date']) ?>"></label>
            <label>Description<textarea name="edu_desc[]" placeholder="Relevant courses, honors"><?= htmlspecialchars($ed['description']) ?></textarea></label>
            <label>Institution email<input name="edu_email[]" type="email" placeholder="registrar@university.ac.tz" value="<?= htmlspecialchars($ed['verify_email']) ?>"></label>
            <hr>
          </div>
        <?php endforeach; endif; ?>
      </div>
      <button type="button" class="button secondary" onclick="addEdu()">Add education</button>
    </fieldset>

    <!-- WORK EXPERIENCE -->
    <fieldset id="work-set"><legend>Work Experience</legend>
      <div id="work-list">
        <?php if (!empty($works)): foreach ($works as $w): ?>
          <div class="work-item">
            <input type="hidden" name="work_id[]" value="<?= htmlspecialchars($w['id']) ?>">
            <input name="work_company[]" placeholder="Company" value="<?= htmlspecialchars($w['company']) ?>">
            <input name="work_role[]" placeholder="Role" value="<?= htmlspecialchars($w['role']) ?>">
            <label>Start<input type="date" name="work_start[]" value="<?= htmlspecialchars($w['start_date']) ?>"></label>
            <label>End<input type="date" name="work_end[]" value="<?= htmlspecialchars($w['end_date']) ?>"></label>
            <label>Responsibilities<textarea name="work_resp[]"><?= htmlspecialchars($w['responsibilities']) ?></textarea></label>
            <label>HR email<input name="work_email[]" type="email" value="<?= htmlspecialchars($w['verify_email']) ?>"></label>
            <hr>
          </div>
        <?php endforeach; endif; ?>
      </div>
      <button type="button" class="button secondary" onclick="addWork()">Add job</button>
    </fieldset>

    <!-- SKILLS -->
    <fieldset id="skills-set"><legend>Skills</legend>
      <div id="skills-list">
        <?php if (!empty($skills)): foreach ($skills as $s): ?>
          <div class="skill-item">
            <input type="hidden" name="skill_id[]" value="<?= htmlspecialchars($s['id']) ?>">
            <input name="skill_name[]" placeholder="Skill" value="<?= htmlspecialchars($s['skill_name']) ?>">
            <select name="skill_level[]">
              <option <?= ($s['level'] ?? '') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
              <option <?= ($s['level'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
              <option <?= ($s['level'] ?? '') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
              <option <?= ($s['level'] ?? '') === 'Expert' ? 'selected' : '' ?>>Expert</option>
            </select>
          </div>
        <?php endforeach; endif; ?>
      </div>
      <button type="button" class="button secondary" onclick="addSkill()">Add skill</button>
    </fieldset>

    <!-- REFEREES -->
    <fieldset id="ref-set"><legend>Referees</legend>
      <div id="ref-list">
        <?php if (!empty($refs)): foreach ($refs as $r): ?>
          <div class="ref-item">
            <input type="hidden" name="ref_id[]" value="<?= htmlspecialchars($r['id']) ?>">
            <input name="ref_name[]" placeholder="Name" value="<?= htmlspecialchars($r['name']) ?>">
            <input name="ref_email[]" type="email" placeholder="Email" value="<?= htmlspecialchars($r['email']) ?>">
            <input name="ref_relation[]" placeholder="Relation" value="<?= htmlspecialchars($r['relation']) ?>">
            <input name="ref_phone[]" placeholder="Phone" value="<?= htmlspecialchars($r['phone']) ?>">
            <hr>
          </div>
        <?php endforeach; endif; ?>
      </div>
      <button type="button" class="button secondary" onclick="addRef()">Add referee</button>
    </fieldset>

    <div class="button-group">
      <button type="submit" class="button">💾 Save CV</button>
    </div>
  </form>

  <!-- OTHER ACTIONS -->
  <div class="button-group">
    <form method="post" action="send_verifications.php" style="display:inline;">
      <input type="hidden" name="cv_id" value="<?= htmlspecialchars($cv_id) ?>">
      <button type="submit" class="button">📧 Send verification emails</button>
    </form>

    <button type="button" class="button" onclick="window.location.href='review_cv.php'">👁 Review CV</button>

    <form method="post" action="reset_cv.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete your CV and start fresh? All data will be lost.');">
      <button type="submit" class="button reset-button">🗑 Reset CV</button>
    </form>
  </div>
</div>

<script src="assets/script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
          