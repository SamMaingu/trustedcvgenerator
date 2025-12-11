
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;
require_once __DIR__.'/../config/db.php';


$cv_id = (int)($_GET['cv_id'] ?? 0);
$stmt = $pdo->prepare("SELECT cvs.*, users.full_name, users.email AS uemail FROM cvs JOIN users ON users.id=cvs.user_id WHERE cvs.id=?");
$stmt->execute([$cv_id]);
$cvRow = $stmt->fetch();
if (!$cvRow) { exit('CV not found'); }

$edu = $pdo->prepare("SELECT * FROM education WHERE cv_id=?"); $edu->execute([$cv_id]); $eduList = $edu->fetchAll();
$work = $pdo->prepare("SELECT * FROM work_experience WHERE cv_id=?"); $work->execute([$cv_id]); $workList = $work->fetchAll();
$skills = $pdo->prepare("SELECT * FROM skills WHERE cv_id=?"); $skills->execute([$cv_id]); $skills = $skills->fetchAll();
$refs = $pdo->prepare("SELECT * FROM referees WHERE cv_id=?"); $refs->execute([$cv_id]); $refs = $refs->fetchAll();

$user = ['full_name'=>$cvRow['full_name'],'email'=>$cvRow['uemail']];
$cv = $cvRow;

ob_start();
include __DIR__ . '/../templates/cv_basic.php';
$html = ob_get_clean();

$mpdf = new Mpdf(['tempDir' => __DIR__.'/../tmp']);
$mpdf->WriteHTML($html);
$mpdf->Output('cv.pdf', 'I');