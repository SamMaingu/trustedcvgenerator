<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit('Auth required');
}
require_once __DIR__.'/../config/db.php';

$cv_id = (int)($_POST['cv_id'] ?? 0);
if ($cv_id <= 0) {
    $_SESSION['cv_save_message'] = "Invalid CV ID.";
    header("Location: dashboard.php");
    exit;
}

try {
    /* ---------- MAIN CV ---------- */
    $updateStmt = $pdo->prepare("UPDATE cvs SET title=?, summary=?, phone=?, address=?, website=? WHERE id=?");
    $updateStmt->execute([
        $_POST['title'] ?? null,
        $_POST['summary'] ?? null,
        $_POST['phone'] ?? null,
        $_POST['address'] ?? null,
        $_POST['website'] ?? null,
        $cv_id
    ]);

    /* ---------- EDUCATION ---------- */
    $existingEduStmt = $pdo->prepare("SELECT id FROM education WHERE cv_id=?");
    $existingEduStmt->execute([$cv_id]);
    $existingEduIds = $existingEduStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $submittedEduIds = array_values(array_filter($_POST['edu_id'] ?? [], function($v){ return $v !== '' && $v !== null; }));
    $toDeleteEdu = array_diff($existingEduIds, $submittedEduIds);
    if (!empty($toDeleteEdu)) {
        $placeholders = implode(',', array_fill(0, count($toDeleteEdu), '?'));
        $delStmt = $pdo->prepare("DELETE FROM education WHERE id IN ($placeholders) AND cv_id=?");
        $delStmt->execute(array_merge($toDeleteEdu, [$cv_id]));
    }

    $edu_insts = $_POST['edu_institution'] ?? [];
    foreach ($edu_insts as $i => $inst) {
        $id = $_POST['edu_id'][$i] ?? null;
        $degree = $_POST['edu_degree'][$i] ?? null;
        $start = $_POST['edu_start'][$i] ?? null;
        $end = $_POST['edu_end'][$i] ?? null;
        $desc = $_POST['edu_desc'][$i] ?? null;
        $email = $_POST['edu_email'][$i] ?? null;

        $isEmpty = trim((string)$inst) === '' && trim((string)$degree) === '' && trim((string)$desc) === '' && trim((string)$email) === '' && trim((string)$start) === '' && trim((string)$end) === '';
        if ($isEmpty) continue;

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE education SET institution=?, degree=?, start_date=?, end_date=?, description=?, verify_email=? WHERE id=? AND cv_id=?");
            $stmt->execute([$inst, $degree, $start ?: null, $end ?: null, $desc, $email, $id, $cv_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO education (cv_id,institution,degree,start_date,end_date,description,verify_email) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$cv_id, $inst, $degree, $start ?: null, $end ?: null, $desc, $email]);
        }
    }

    /* ---------- WORK EXPERIENCE ---------- */
    $existingWorkStmt = $pdo->prepare("SELECT id FROM work_experience WHERE cv_id=?");
    $existingWorkStmt->execute([$cv_id]);
    $existingWorkIds = $existingWorkStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $submittedWorkIds = array_values(array_filter($_POST['work_id'] ?? [], function($v){ return $v !== '' && $v !== null; }));
    $toDeleteWork = array_diff($existingWorkIds, $submittedWorkIds);
    if (!empty($toDeleteWork)) {
        $placeholders = implode(',', array_fill(0, count($toDeleteWork), '?'));
        $delStmt = $pdo->prepare("DELETE FROM work_experience WHERE id IN ($placeholders) AND cv_id=?");
        $delStmt->execute(array_merge($toDeleteWork, [$cv_id]));
    }

    $work_companies = $_POST['work_company'] ?? [];
    foreach ($work_companies as $i => $comp) {
        $id = $_POST['work_id'][$i] ?? null;
        $role = $_POST['work_role'][$i] ?? null;
        $start = $_POST['work_start'][$i] ?? null;
        $end = $_POST['work_end'][$i] ?? null;
        $resp = $_POST['work_resp'][$i] ?? null;
        $email = $_POST['work_email'][$i] ?? null;

        $isEmpty = trim((string)$comp) === '' && trim((string)$role) === '' && trim((string)$resp) === '' && trim((string)$email) === '' && trim((string)$start) === '' && trim((string)$end) === '';
        if ($isEmpty) continue;

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE work_experience SET company=?, role=?, start_date=?, end_date=?, responsibilities=?, verify_email=? WHERE id=? AND cv_id=?");
            $stmt->execute([$comp, $role, $start ?: null, $end ?: null, $resp, $email, $id, $cv_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO work_experience (cv_id,company,role,start_date,end_date,responsibilities,verify_email) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$cv_id, $comp, $role, $start ?: null, $end ?: null, $resp, $email]);
        }
    }

    /* ---------- SKILLS ---------- */
    $existingSkillStmt = $pdo->prepare("SELECT id FROM skills WHERE cv_id=?");
    $existingSkillStmt->execute([$cv_id]);
    $existingSkillIds = $existingSkillStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $submittedSkillIds = array_values(array_filter($_POST['skill_id'] ?? [], function($v){ return $v !== '' && $v !== null; }));
    $toDeleteSkill = array_diff($existingSkillIds, $submittedSkillIds);
    if (!empty($toDeleteSkill)) {
        $placeholders = implode(',', array_fill(0, count($toDeleteSkill), '?'));
        $delStmt = $pdo->prepare("DELETE FROM skills WHERE id IN ($placeholders) AND cv_id=?");
        $delStmt->execute(array_merge($toDeleteSkill, [$cv_id]));
    }

    $skill_names = $_POST['skill_name'] ?? [];
    foreach ($skill_names as $i => $skill) {
        $id = $_POST['skill_id'][$i] ?? null;
        $level = $_POST['skill_level'][$i] ?? 'Intermediate';

        $isEmpty = trim((string)$skill) === '';
        if ($isEmpty) continue;

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE skills SET skill_name=?, level=? WHERE id=? AND cv_id=?");
            $stmt->execute([$skill, $level, $id, $cv_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO skills (cv_id,skill_name,level) VALUES (?,?,?)");
            $stmt->execute([$cv_id, $skill, $level]);
        }
    }

    /* ---------- REFEREES ---------- */
    $existingRefStmt = $pdo->prepare("SELECT id FROM referees WHERE cv_id=?");
    $existingRefStmt->execute([$cv_id]);
    $existingRefIds = $existingRefStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $submittedRefIds = array_values(array_filter($_POST['ref_id'] ?? [], function($v){ return $v !== '' && $v !== null; }));
    $toDeleteRef = array_diff($existingRefIds, $submittedRefIds);
    if (!empty($toDeleteRef)) {
        $placeholders = implode(',', array_fill(0, count($toDeleteRef), '?'));
        $delStmt = $pdo->prepare("DELETE FROM referees WHERE id IN ($placeholders) AND cv_id=?");
        $delStmt->execute(array_merge($toDeleteRef, [$cv_id]));
    }

    $ref_names = $_POST['ref_name'] ?? [];
    foreach ($ref_names as $i => $name) {
        $id = $_POST['ref_id'][$i] ?? null;
        $email = $_POST['ref_email'][$i] ?? null;
        $relation = $_POST['ref_relation'][$i] ?? null;
        $phone = $_POST['ref_phone'][$i] ?? null;

        $isEmpty = trim((string)$name) === '' && trim((string)$email) === '' && trim((string)$relation) === '' && trim((string)$phone) === '';
        if ($isEmpty) continue;

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE referees SET name=?, email=?, relation=?, phone=? WHERE id=? AND cv_id=?");
            $stmt->execute([$name, $email, $relation, $phone, $id, $cv_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO referees (cv_id,name,email,relation,phone) VALUES (?,?,?,?,?)");
            $stmt->execute([$cv_id, $name, $email, $relation, $phone]);
        }
    }

    $_SESSION['cv_save_message'] = "✅ CV saved successfully!";
} catch (Exception $e) {
    error_log("CV Save Error: " . $e->getMessage());
    $_SESSION['cv_save_message'] = "❌ Failed to save CV. Please try again.";
}

header("Location: dashboard.php");
exit;
