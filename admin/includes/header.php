<?php
if (!defined('NOVA_APP')) die('Access denied');
$flash = getFlash();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#6366f1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="icon" href="<?= SITE_URL ?>/assets/icons/nova-icon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= SITE_URL ?>/assets/icons/nova-icon.svg">
<link rel="manifest" href="<?= SITE_URL ?>/site.webmanifest">
<title><?= $pageTitle ?? 'لوحة التحكم — ' . SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css?v=20260919-1909">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css?v=20260919-1909">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-modern.css?v=20260919-1924">
</head>
<body>

<div class="admin-layout">
    <aside class="admin-side">
        <div class="admin-brand"><span class="admin-brand-mark">N</span><div><strong>NOVA Admin</strong><small>إدارة المعهد التدريبي</small></div></div>
        <div class="admin-section-label">القائمة الرئيسية</div>
        <nav>
        <a href="<?= SITE_URL ?>/admin/index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>"><span class="nav-icon">▦</span>الرئيسية</a>
        <a href="<?= SITE_URL ?>/admin/users.php" class="<?= $currentPage === 'users' ? 'active' : '' ?>"><span class="nav-icon">♙</span>المستخدمون</a>
        <a href="<?= SITE_URL ?>/admin/courses.php" class="<?= $currentPage === 'courses' ? 'active' : '' ?>"><span class="nav-icon">▤</span>الدورات</a>
        <a href="<?= SITE_URL ?>/admin/categories.php" class="<?= $currentPage === 'categories' ? 'active' : '' ?>"><span class="nav-icon">⌘</span>التصنيفات</a>
        <a href="<?= SITE_URL ?>/admin/instructors.php" class="<?= $currentPage === 'instructors' ? 'active' : '' ?>"><span class="nav-icon">♟</span>المدربون</a>
        <a href="<?= SITE_URL ?>/admin/enrollments.php" class="<?= $currentPage === 'enrollments' ? 'active' : '' ?>"><span class="nav-icon">✓</span>التسجيلات</a>
        <a href="<?= SITE_URL ?>/admin/messages.php" class="<?= $currentPage === 'messages' ? 'active' : '' ?>"><span class="nav-icon">✉</span>الرسائل</a>
        </nav>
        <hr class="side-separator">
        <a href="<?= SITE_URL ?>/index.php"><span class="nav-icon">⌂</span>زيارة الموقع</a>
        <a href="<?= SITE_URL ?>/logout.php" class="side-exit"><span class="nav-icon">⇥</span>تسجيل الخروج</a>
    </aside>

    <main class="admin-main">
        <div class="admin-top">
            <h1><?= $pageTitle ?? 'لوحة التحكم' ?></h1>
            <div class="admin-user"><span class="admin-user-avatar">N</span><span><?= htmlspecialchars($_SESSION['user_name'] ?? 'المشرف') ?></span></div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <div><?= $flash['message'] ?></div>
            </div>
        <?php endif; ?>
