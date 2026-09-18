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
<title><?= $pageTitle ?? 'لوحة التحكم — ' . SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
<style>
.admin-layout{display:grid;grid-template-columns:250px 1fr;min-height:100vh}
.admin-side{background:var(--grad-dark);color:#fff;padding:24px 16px;position:sticky;top:0;height:100vh;overflow-y:auto}
.admin-side h2{font-size:1.3rem;font-weight:900;margin-bottom:24px;padding:0 12px}
.admin-side a{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:11px;color:rgba(255,255,255,.75);font-weight:600;font-size:.9rem;margin-bottom:4px;transition:.2s}
.admin-side a:hover,.admin-side a.active{background:rgba(255,255,255,.12);color:#fff}
.admin-main{padding:30px;background:var(--bg)}
.admin-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:26px;flex-wrap:wrap;gap:12px}
.admin-top h1{font-size:1.7rem;font-weight:900}
@media(max-width:768px){.admin-layout{grid-template-columns:1fr}.admin-side{height:auto;position:static}}
</style>
</head>
<body>

<div class="admin-layout">
    <aside class="admin-side">
        <h2>⚙️ لوحة التحكم</h2>
        <a href="<?= SITE_URL ?>/admin/index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>">📊 الرئيسية</a>
        <a href="<?= SITE_URL ?>/admin/users.php" class="<?= $currentPage === 'users' ? 'active' : '' ?>">👥 المستخدمون</a>
        <a href="<?= SITE_URL ?>/admin/courses.php" class="<?= $currentPage === 'courses' ? 'active' : '' ?>">📚 الدورات</a>
        <a href="<?= SITE_URL ?>/admin/categories.php" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">📁 التصنيفات</a>
        <a href="<?= SITE_URL ?>/admin/instructors.php" class="<?= $currentPage === 'instructors' ? 'active' : '' ?>">👨‍🏫 المدربون</a>
        <a href="<?= SITE_URL ?>/admin/enrollments.php" class="<?= $currentPage === 'enrollments' ? 'active' : '' ?>">✍️ التسجيلات</a>
        <a href="<?= SITE_URL ?>/admin/messages.php" class="<?= $currentPage === 'messages' ? 'active' : '' ?>">📧 الرسائل</a>
        <hr style="border:none;border-top:1px solid rgba(255,255,255,.15);margin:14px 8px">
        <a href="<?= SITE_URL ?>/index.php">🏠 الموقع</a>
        <a href="<?= SITE_URL ?>/logout.php" style="color:#fca5a5">🚪 خروج</a>
    </aside>

    <main class="admin-main">
        <div class="admin-top">
            <h1><?= $pageTitle ?? 'لوحة التحكم' ?></h1>
            <div>👤 <?= $_SESSION['user_name'] ?? '' ?></div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <div><?= $flash['message'] ?></div>
            </div>
        <?php endif; ?>