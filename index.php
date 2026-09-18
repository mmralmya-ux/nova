<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// آخر 6 دورات
$stmt = $pdo->query("SELECT c.*, cat.name AS cat_name FROM courses c 
                     LEFT JOIN categories cat ON cat.id = c.category_id 
                     WHERE c.is_active = 1 ORDER BY c.id DESC LIMIT 6");
$courses = $stmt->fetchAll();

// التصنيفات
$stmt = $pdo->query("SELECT * FROM categories LIMIT 4");
$categories = $stmt->fetchAll();

$pageTitle = 'الرئيسية — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>مرحباً بك في معهد NOVA التدريبي</h1>
        <p>منصة تعليمية عربية حديثة، توفر دورات تدريبية في مختلف المجالات بمعايير عالمية.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            <a href="courses.php" class="btn btn-lg" style="background:#fff;color:var(--primary)">🔍 تصفح الدورات</a>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-lg btn-outline">✨ إنشاء حساب</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- التصنيفات -->
    <h2 style="text-align:center;font-size:1.8rem;font-weight:900;margin-bottom:24px">تصنيفات الدورات</h2>
    <div class="grid grid-4" style="margin-bottom:50px">
        <?php foreach ($categories as $cat): ?>
            <a href="courses.php?category=<?= $cat['id'] ?>" class="course-card" style="text-align:center;padding:30px 20px">
                <div style="font-size:3rem;margin-bottom:10px"><?= $cat['icon'] ?></div>
                <div style="font-weight:800"><?= clean($cat['name']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- الدورات المميزة -->
    <h2 style="text-align:center;font-size:1.8rem;font-weight:900;margin-bottom:24px">أحدث الدورات</h2>
    <?php if (empty($courses)): ?>
        <div class="empty">
            <div class="icon">📚</div>
            <p>لا توجد دورات بعد</p>
        </div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($courses as $c): ?>
                <div class="course-card">
                    <div class="course-img">
                        <?php if ($c['image']): ?>
                            <img src="<?= SITE_URL ?>/uploads/courses/<?= $c['image'] ?>" alt="<?= clean($c['title']) ?>">
                        <?php else: ?>
                            📚
                        <?php endif; ?>
                    </div>
                    <div class="course-body">
                        <span class="badge badge-primary"><?= clean($c['cat_name'] ?? 'دورة') ?></span>
                        <h3 class="course-title"><?= clean($c['title']) ?></h3>
                        <div class="course-meta">
                            <span>⏱ <?= $c['duration_hours'] ?> ساعة</span>
                            <span>📊 <?= levelName($c['level']) ?></span>
                        </div>
                        <div class="course-price"><?= formatPrice($c['price']) ?></div>
                        <a href="course.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-block">التفاصيل</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:40px;margin-bottom:40px">
        <a href="courses.php" class="btn btn-lg btn-primary">عرض كل الدورات ←</a>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>