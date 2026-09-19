<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$mediaVideoUrl = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect(SITE_URL . '/courses.php');

$stmt = $pdo->prepare("SELECT c.*, cat.name AS cat_name, cat.icon AS cat_icon, 
                              i.full_name AS instructor_name, i.specialty AS instructor_specialty
                       FROM courses c
                       LEFT JOIN categories cat ON cat.id = c.category_id
                       LEFT JOIN instructors i ON i.id = c.instructor_id
                       WHERE c.id = ? AND c.is_active = 1");
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) redirect(SITE_URL . '/courses.php');
$mediaVideoUrl = !empty($course['video_url']) && filter_var($course['video_url'], FILTER_VALIDATE_URL)
    ? $course['video_url']
    : (!empty($course['video_url']) ? SITE_URL . '/uploads/videos/' . rawurlencode($course['video_url']) : '');

// هل المستخدم مسجل في هذه الدورة؟
$isEnrolled = false;
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);
    $isEnrolled = (bool)$stmt->fetch();
}

// عدد المسجلين
$stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
$stmt->execute([$id]);
$enrolledCount = $stmt->fetchColumn();

$pageTitle = clean($course['title']) . ' — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <p style="margin-bottom:20px;color:var(--muted);font-size:.9rem">
        <a href="index.php">الرئيسية</a> › 
        <a href="courses.php">الدورات</a> › 
        <?= clean($course['title']) ?>
    </p>

    <div class="grid grid-2">
        <!-- الصورة والمعلومات الجانبية -->
        <div>
            <div class="course-img" style="border-radius:20px;height:320px;font-size:5rem;margin-bottom:20px">
                <?php if (!empty($course['image'])): ?><img src="<?= SITE_URL ?>/uploads/courses/<?= rawurlencode($course['image']) ?>" alt="<?= clean($course['title']) ?>"><?php else: ?><?= $course['cat_icon'] ?? '📚' ?><?php endif; ?>
            </div>
            <?php if ($mediaVideoUrl): ?><div class="card media-card"><h3>فيديو تعريفي</h3><video class="course-video" controls preload="metadata" src="<?= htmlspecialchars($mediaVideoUrl) ?>"></video></div><?php endif; ?>
            <div class="card" style="padding:20px">
                <h4 style="margin-bottom:14px;font-weight:800">معلومات الدورة</h4>
                <table style="min-width:auto">
                    <tr><td style="padding:10px 0">📊 المستوى</td><td style="font-weight:800"><?= levelName($course['level']) ?></td></tr>
                    <tr><td style="padding:10px 0">⏱ المدة</td><td style="font-weight:800"><?= $course['duration_hours'] ?> ساعة</td></tr>
                    <tr><td style="padding:10px 0">👥 المقاعد</td><td style="font-weight:800"><?= $enrolledCount ?> / <?= $course['seats'] ?></td></tr>
                    <?php if ($course['instructor_name']): ?>
                        <tr><td style="padding:10px 0">👨‍🏫 المدرب</td><td style="font-weight:800"><?= clean($course['instructor_name']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- التفاصيل الرئيسية -->
        <div>
            <span class="badge badge-primary"><?= clean($course['cat_name'] ?? 'دورة') ?></span>
            <h1 style="font-size:2rem;font-weight:900;margin:14px 0"><?= clean($course['title']) ?></h1>
            
            <div style="font-size:2rem;font-weight:900;color:var(--primary);margin-bottom:20px">
                <?= formatPrice($course['price']) ?>
            </div>

            <h3 style="margin-bottom:12px;font-weight:800">📖 وصف الدورة</h3>
            <p style="color:var(--text-2);line-height:1.9;margin-bottom:24px"><?= nl2br(clean($course['description'])) ?></p>

            <?php if ($isEnrolled): ?>
                <div class="alert alert-success">
                    <div>✅ أنت مسجل في هذه الدورة بالفعل</div>
                </div>
                <a href="my-courses.php" class="btn btn-primary btn-block btn-lg">📚 اذهب لدوراتي</a>
            <?php elseif (!isLoggedIn()): ?>
                <div class="alert alert-info">
                    <div>ℹ️ يجب تسجيل الدخول للتسجيل في الدورة</div>
                </div>
                <a href="login.php" class="btn btn-primary btn-block btn-lg">🔑 سجّل دخولك للتسجيل</a>
            <?php else: ?>
                <a href="enroll.php?id=<?= $course['id'] ?>" class="btn btn-primary btn-block btn-lg">✍️ سجّل في الدورة الآن</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
