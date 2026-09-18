<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$stmt = $pdo->prepare("SELECT e.*, c.title, c.price, c.duration_hours, c.level, c.image,
                              cat.name AS cat_name, cat.icon AS cat_icon
                       FROM enrollments e
                       JOIN courses c ON c.id = e.course_id
                       LEFT JOIN categories cat ON cat.id = c.category_id
                       WHERE e.user_id = ?
                       ORDER BY e.enrolled_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$enrollments = $stmt->fetchAll();

$pageTitle = 'دوراتي — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <div class="dash-grid">
        <aside class="dash-side">
            <h3 style="margin-bottom:14px;font-weight:900">حسابي</h3>
            <a href="dashboard.php">📊 نظرة عامة</a>
            <a href="my-courses.php" class="active">📚 دوراتي</a>
            <a href="courses.php">🔍 تصفح الدورات</a>
            <a href="profile.php">👤 الملف الشخصي</a>
            <a href="logout.php" style="color:var(--danger)">🚪 تسجيل الخروج</a>
        </aside>

        <div class="dash-content">
            <h2 style="margin-bottom:8px">📚 دوراتي</h2>
            <p style="color:var(--text-2);margin-bottom:24px">الدورات التي سجلت فيها</p>

            <?php if (empty($enrollments)): ?>
                <div class="empty">
                    <div class="icon">📚</div>
                    <h3>لا توجد دورات مسجلة</h3>
                    <p>ابدأ بتصفح الدورات المتاحة</p>
                    <a href="courses.php" class="btn btn-primary" style="margin-top:16px">🔍 تصفح الدورات</a>
                </div>
            <?php else: ?>
                <div class="grid grid-2">
                    <?php foreach ($enrollments as $e): ?>
                        <div class="course-card">
                            <div class="course-img">
                                <?= $e['cat_icon'] ?? '📚' ?>
                            </div>
                            <div class="course-body">
                                <span class="badge badge-primary"><?= clean($e['cat_name'] ?? 'دورة') ?></span>
                                <h3 class="course-title"><?= clean($e['title']) ?></h3>
                                <div class="course-meta">
                                    <span>⏱ <?= $e['duration_hours'] ?> ساعة</span>
                                    <span>📊 <?= levelName($e['level']) ?></span>
                                    <span>📅 <?= date('Y-m-d', strtotime($e['enrolled_at'])) ?></span>
                                </div>
                                <div>
                                    <?php
                                    $badgeClass = 'badge-warning';
                                    if ($e['status'] === 'active') $badgeClass = 'badge-primary';
                                    elseif ($e['status'] === 'completed') $badgeClass = 'badge-success';
                                    elseif ($e['status'] === 'cancelled') $badgeClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= statusName($e['status']) ?></span>
                                </div>
                                <a href="course.php?id=<?= $e['course_id'] ?>" class="btn btn-outline btn-block" style="margin-top:10px">عرض التفاصيل</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>