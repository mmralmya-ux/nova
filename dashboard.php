<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$user = currentUser();
if (!$user) {
    logout();
    redirect(SITE_URL . '/login.php');
}

// إحصائيات
$stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ?");
$stmt->execute([$user['id']]);
$enrollCount = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user['id']]);
$completedCount = $stmt->fetchColumn();

$pageTitle = 'لوحة التحكم — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <div class="dash-grid">
        <aside class="dash-side">
            <h3 style="margin-bottom:14px;font-weight:900">حسابي</h3>
            <a href="dashboard.php" class="active">📊 نظرة عامة</a>
            <a href="my-courses.php">📚 دوراتي</a>
            <a href="courses.php">🔍 تصفح الدورات</a>
            <a href="profile.php">👤 الملف الشخصي</a>
            <a href="logout.php" style="color:var(--danger)">🚪 تسجيل الخروج</a>
        </aside>

        <div class="dash-content">
            <h2 style="margin-bottom:8px">مرحباً، <?= clean($user['full_name']) ?> 👋</h2>
            <p style="color:var(--text-2);margin-bottom:24px">إليك ملخص حسابك</p>

            <div class="grid grid-3" style="margin-bottom:30px">
                <div class="stat">
                    <div class="num"><?= $enrollCount ?></div>
                    <div class="lbl">الدورات المسجلة</div>
                </div>
                <div class="stat">
                    <div class="num"><?= $completedCount ?></div>
                    <div class="lbl">الدورات المكتملة</div>
                </div>
                <div class="stat">
                    <div class="num"><?= date('Y') ?></div>
                    <div class="lbl">عضو منذ <?= date('Y', strtotime($user['created_at'])) ?></div>
                </div>
            </div>

            <h3 style="margin-bottom:16px;font-weight:800">⚡ إجراءات سريعة</h3>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <a href="courses.php" class="btn btn-primary">🔍 تصفح الدورات</a>
                <a href="my-courses.php" class="btn btn-outline">📚 دوراتي</a>
                <a href="profile.php" class="btn btn-outline">👤 الملف الشخصي</a>
            </div>

            <?php if (isAdmin()): ?>
                <div class="alert alert-info" style="margin-top:26px">
                    <div>⚙️ أنت مشرف! <a href="admin/index.php" style="font-weight:800">اذهب للوحة التحكم الكاملة</a></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>