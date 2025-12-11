<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user_id'];

// Start transaction to safely delete all CV-related data
$pdo->beginTransaction();

try {
    // Get CV ID for this user
    $stmt = $pdo->prepare("SELECT id FROM cvs WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cv = $stmt->fetch();
    $cv_id = $cv['id'] ?? null;

    if ($cv_id) {
        // Delete related tables
        $tables = ['education', 'work_experience', 'skills', 'referees', 'verifications'];
        foreach ($tables as $table) {
            $delStmt = $pdo->prepare("DELETE FROM {$table} WHERE cv_id = ?");
            $delStmt->execute([$cv_id]);
        }

        // Delete CV itself
        $delCV = $pdo->prepare("DELETE FROM cvs WHERE id = ?");
        $delCV->execute([$cv_id]);
    }

    $pdo->commit();

    // Redirect to create new CV page or dashboard
    header("Location: dashboard.php?reset=success");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    exit("Error resetting CV: " . $e->getMessage());
}
