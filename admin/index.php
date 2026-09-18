<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'courses' => $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
    'enrollments' => $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn(),
    'messages' => $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn(),
];

$pageTitle = 'لوحة التحكم';
require_once 'includes/header.php';
?>

<div class="admin-welcome">
    <div><h2>مرحباً بك في لوحة الإدارة</h2><p>تحكم في محتوى NOVA وتابع نشاط المنصة من مكان واحد.</p></div>
    <a href="<?= SITE_URL ?>/index.php" class="btn">عرض الموقع ↗</a>
</div>

<div class="admin-stats">
    <div class="admin-stat" style="--stat-soft:#e3fafc;--stat-color:#087f8c"><div class="admin-stat-head"><span>المستخدمون</span><span class="admin-stat-icon">♙</span></div><strong class="admin-stat-number"><?= $stats['users'] ?></strong></div>
    <div class="admin-stat" style="--stat-soft:#fff4e6;--stat-color:#e67700"><div class="admin-stat-head"><span>الدورات</span><span class="admin-stat-icon">▤</span></div><strong class="admin-stat-number"><?= $stats['courses'] ?></strong></div>
    <div class="admin-stat" style="--stat-soft:#ebfbee;--stat-color:#2b8a3e"><div class="admin-stat-head"><span>التسجيلات</span><span class="admin-stat-icon">✓</span></div><strong class="admin-stat-number"><?= $stats['enrollments'] ?></strong></div>
    <div class="admin-stat" style="--stat-soft:#f3f0ff;--stat-color:#6741d9"><div class="admin-stat-head"><span>رسائل جديدة</span><span class="admin-stat-icon">✉</span></div><strong class="admin-stat-number"><?= $stats['messages'] ?></strong></div>
</div>

<div class="card">
    <h2>إجراءات سريعة</h2>
    <div class="admin-actions">
        <a href="courses.php" class="admin-action"><span class="admin-action-icon">▤</span><span>إدارة الدورات</span></a>
        <a href="users.php" class="admin-action"><span class="admin-action-icon">♙</span><span>إدارة المستخدمين</span></a>
        <a href="messages.php" class="admin-action"><span class="admin-action-icon">✉</span><span>مراجعة الرسائل</span></a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
