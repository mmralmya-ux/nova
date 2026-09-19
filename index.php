<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$courses = $pdo->query("SELECT c.*, cat.name AS cat_name FROM courses c LEFT JOIN categories cat ON cat.id = c.category_id WHERE c.is_active = 1 ORDER BY c.id DESC LIMIT 6")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories LIMIT 4")->fetchAll();
$pageTitle = 'تعلّم بذكاء — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="nova-home">
    <section class="nova-hero container">
        <div class="hero-copy">
            <span class="hero-kicker"><i></i> منصة تعلم عربية بطموح عالمي</span>
            <h1>المهارة التي تبحث عنها<br><em>تبدأ من هنا.</em></h1>
            <p>تعلّم من دورات عملية صُممت لتمنحك معرفة قابلة للتطبيق، وتبني لك مستقبلاً أقوى خطوة بعد خطوة.</p>
            <div class="hero-cta"><a href="courses.php" class="btn btn-primary btn-lg">استكشف الدورات <span>←</span></a><?php if (!isLoggedIn()): ?><a href="register.php" class="hero-link">ابدأ مجاناً <span>↗</span></a><?php endif; ?></div>
            <div class="hero-proof"><div class="avatar-stack"><b>ن</b><b>م</b><b>س</b></div><span><strong>+1,200</strong> متعلم بدأ رحلته معنا</span></div>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="hero-glow"></div><div class="orbit orbit-a"></div><div class="orbit orbit-b"></div>
            <div class="learning-card"><div class="learning-icon">✦</div><small>مسارك التعليمي</small><strong>تطوير مهاراتك<br>بأسلوبك الخاص</strong><div class="progress-line"><i></i></div><span>68% من المسار مكتمل</span></div>
            <div class="floating-pill pill-top">✦ تعلّم مستمر</div><div class="floating-pill pill-bottom">✓ إنجاز جديد</div>
        </div>
    </section>

    <section class="trust-strip"><div class="container trust-inner"><span>مصمم للمتعلمين الطموحين</span><div><b>تعلّم عملي</b><b>محتوى عربي</b><b>تقدّم واضح</b><b>مجتمع داعم</b></div></div></section>

    <section class="container home-section category-section"><div class="section-heading"><div><span class="eyebrow">EXPLORE YOUR PATH</span><h2>ماذا تريد أن تتعلم؟</h2></div><a href="courses.php">كل التصنيفات <span>←</span></a></div><div class="category-grid"><?php foreach ($categories as $cat): ?><a href="courses.php?category=<?= $cat['id'] ?>" class="category-tile"><span class="category-icon"><?= clean($cat['icon']) ?></span><span><strong><?= clean($cat['name']) ?></strong><small><?= clean($cat['description'] ?? 'ابدأ التعلم الآن') ?></small></span><b>↗</b></a><?php endforeach; ?></div></section>

    <section class="container home-section courses-section"><div class="section-heading"><div><span class="eyebrow">CURATED FOR YOU</span><h2>دورات تفتح لك آفاقاً جديدة</h2></div><a href="courses.php">عرض الكل <span>←</span></a></div><?php if ($courses): ?><div class="modern-course-grid"><?php foreach ($courses as $c): ?><article class="modern-course-card"><div class="modern-course-cover"><?php if (!empty($c['image'])): ?><img src="<?= SITE_URL ?>/uploads/courses/<?= rawurlencode($c['image']) ?>" alt="<?= clean($c['title']) ?>"><?php else: ?><span>✦</span><?php endif; ?><label><?= clean($c['cat_name'] ?? 'دورة') ?></label></div><div class="modern-course-body"><h3><?= clean($c['title']) ?></h3><div class="modern-meta"><span>◷ <?= (int)$c['duration_hours'] ?> ساعة</span><span>◈ <?= levelName($c['level']) ?></span></div><div class="modern-course-foot"><strong><?= formatPrice($c['price']) ?></strong><a href="course.php?id=<?= (int)$c['id'] ?>">التفاصيل <span>←</span></a></div></div></article><?php endforeach; ?></div><?php endif; ?></section>

    <section class="container final-cta"><div><span class="eyebrow">YOUR NEXT CHAPTER</span><h2>جاهز تبدأ النسخة الأفضل منك؟</h2><p>خطوة واحدة تفصلك عن مهارة جديدة وفرصة أكبر.</p></div><a href="courses.php" class="btn btn-primary btn-lg">ابدأ رحلة التعلم <span>←</span></a></section>
</div>

<?php require_once 'includes/footer.php'; ?>
