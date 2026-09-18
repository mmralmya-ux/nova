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

<div class="grid grid-4" style="margin-bottom:30px">
    <div class="stat">
        <div class="num"><?= $stats['users'] ?></div>
        <div class="lbl">المستخدمون</div>
    </div>
    <div class="stat">
        <div class="num"><?= $stats['courses'] ?></div>
        <div class="lbl">الدورات</div>
    </div>
    <div class="stat">
        <div class="num"><?= $stats['enrollments'] ?></div>
        <div class="lbl">التسجيلات</div>
    </div>
    <div class="stat">
        <div class="num"><?= $stats['messages'] ?></div>
        <div class="lbl">رسائل جديدة</div>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom:16px">⚡ إجراءات سريعة</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="courses.php" class="btn btn-primary">📚 إدارة الدورات</a>
        <a href="users.php" class="btn btn-outline">👥 إدارة المستخدمين</a>
        <a href="messages.php" class="btn btn-outline">📧 الرسائل</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>