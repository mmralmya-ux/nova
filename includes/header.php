<?php
if (!defined('NOVA_APP')) die('Access denied');
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $pageTitle ?? SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css?v=20260919-1909">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern-home.css?v=20260919-1924">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="<?= SITE_URL ?>/index.php" class="brand">
            <span class="brand-icon">N</span>
            <span class="brand-text">NOVA<small>معهد تدريبي</small></span>
        </a>
        <nav class="main-nav">
            <a href="<?= SITE_URL ?>/index.php">الرئيسية</a>
            <a href="<?= SITE_URL ?>/courses.php">الدورات</a>
            <a href="<?= SITE_URL ?>/about.php">عن المعهد</a>
            <a href="<?= SITE_URL ?>/contact.php">تواصل معنا</a>
        </nav>
        <div class="header-actions">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="<?= SITE_URL ?>/admin/index.php" class="btn btn-outline btn-sm">⚙️ لوحة التحكم</a>
                <?php endif; ?>
                <a href="<?= SITE_URL ?>/dashboard.php" class="btn btn-primary btn-sm">👤 حسابي</a>
                <a href="<?= SITE_URL ?>/logout.php" class="btn btn-outline btn-sm">خروج</a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/login.php" class="btn btn-outline btn-sm">دخول</a>
                <a href="<?= SITE_URL ?>/register.php" class="btn btn-primary btn-sm">تسجيل</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="site-main">
<?php if ($flash): ?>
<div class="container" style="padding-top:20px">
    <div class="alert alert-<?= $flash['type'] ?>">
        <div><?= $flash['message'] ?></div>
    </div>
</div>
<?php endif; ?>
