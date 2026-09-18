<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect(SITE_URL . '/courses.php');

// تحقق من وجود الدورة
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND is_active = 1");
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) {
    setFlash('error', 'الدورة غير موجودة');
    redirect(SITE_URL . '/courses.php');
}

// تحقق أنه غير مسجل مسبقاً
$stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->execute([$_SESSION['user_id'], $id]);
if ($stmt->fetch()) {
    setFlash('warning', 'أنت مسجل في هذه الدورة بالفعل');
    redirect(SITE_URL . '/my-courses.php');
}

// تحقق من المقاعد
$stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
$stmt->execute([$id]);
$enrolledCount = $stmt->fetchColumn();
if ($enrolledCount >= $course['seats']) {
    setFlash('error', 'عذراً، الدورة ممتلئة');
    redirect(SITE_URL . '/course.php?id=' . $id);
}

// سجّل
$stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'pending')");
$stmt->execute([$_SESSION['user_id'], $id]);

setFlash('success', '🎉 تم تسجيلك في الدورة بنجاح!');
redirect(SITE_URL . '/my-courses.php');