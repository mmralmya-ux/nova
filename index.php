<?php

define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$courses = $pdo->query("SELECT c.*, cat.name AS cat_name FROM courses c LEFT JOIN categories cat ON cat.id = c.category_id WHERE c.is_active = 1 ORDER BY c.id DESC LIMIT 6")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC LIMIT 6")->fetchAll();
$pageTitle = 'مستقبل تعلمك يبدأ هنا — ' . SITE_NAME;
require_once 'includes/header.php';
?>
<div class="nova-home nova-home-v2">
    <section class="v2-hero">
        <div class="container v2-hero-grid">
            <div class="v2-hero-copy">
                <span class="v2-label"><i></i> تعلّم بشكل مختلف</span>
                <h1>ابنِ مهارتك.<br><span>اصنع مستقبلك.</span></h1>
                <p>منصة تعليمية عربية تمنحك المعرفة والأدوات والمسار الواضح لتنتقل من الفضول إلى الاحتراف.</p>
                <div class="v2-actions">
                    <a href="courses.php" class="btn btn-primary btn-lg">ابدأ التعلم الآن <b>←</b></a>
                    <a href="#why-nova" class="v2-watch"><span>✦</span> لماذا NOVA؟</a>
                </div>
                <div class="v2-rating"><strong>4.9</strong><span class="stars">★★★★★</span><small>تجربة مصممة لأكثر من 1,200 متعلم</small></div>
            </div>
            <div class="v2-hero-media">
                <img src="<?= SITE_URL ?>/assets/images/nova-hero-a.png" alt="تجربة تعلم عصرية في منصة NOVA" loading="eager">
                <div class="hero-media-shade"></div>
                <div class="hero-media-card media-card-top"><span>✦</span><div><b>تعلّم بذكاء</b><small>مسارات عملية وواضحة</small></div></div>
                <div class="hero-media-card media-card-bottom"><strong>+24</strong><span>ساعة محتوى عملي</span></div>
            </div>
        </div>
    </section>

    <section class="v2-metrics" aria-label="إحصاءات NOVA">
        <div class="container metrics-grid"><div><strong>+1,200</strong><span>متعلم نشط</span></div><div><strong>+24</strong><span>ساعة محتوى عملي</span></div><div><strong>6</strong><span>مسارات متخصصة</span></div><div><strong>100%</strong><span>تجربة عربية</span></div></div>
    </section>

    <section class="container v2-section" id="why-nova">
        <div class="v2-section-head"><div><span class="v2-overline">WHY NOVA</span><h2>كل ما تحتاجه لتتقدم بثقة</h2></div><p class="section-intro">تجربة تعليمية تجمع بين الوضوح، التطبيق، والمتابعة في مكان واحد.</p></div>
        <div class="benefit-grid">
            <article class="benefit-card"><span class="benefit-icon">✦</span><h3>تعلم عملي</h3><p>محتوى واضح وتطبيقات تساعدك على تحويل المعرفة إلى مهارة قابلة للاستخدام.</p><b>01</b></article>
            <article class="benefit-card"><span class="benefit-icon">◈</span><h3>مسار واضح</h3><p>اختر هدفك واتبع خطوات مرتبة من البداية حتى بناء ملفك ومشاريعك.</p><b>02</b></article>
            <article class="benefit-card"><span class="benefit-icon">↗</span><h3>تقدم ملموس</h3><p>تابع دوراتك وتقدمك من لوحة شخصية مصممة لتبقيك على المسار.</p><b>03</b></article>
        </div>
    </section>

    <section class="container v2-section">
        <div class="v2-section-head"><div><span class="v2-overline">LEARN WITH PURPOSE</span><h2>اختر المسار الذي يشبه طموحك</h2></div><a href="courses.php">استكشف كل المسارات <b>←</b></a></div>
        <div class="v2-path-grid"><?php foreach ($categories as $cat): ?><a class="v2-path" href="courses.php?category=<?= (int)$cat['id'] ?>" style="--path-image:url('<?= !empty($cat['image']) ? SITE_URL . '/uploads/categories/' . rawurlencode($cat['image']) : '' ?>')"><div class="path-overlay"></div><div class="path-content"><span><?= clean($cat['icon']) ?></span><h3><?= clean($cat['name']) ?></h3><p><?= clean($cat['description'] ?? 'ابدأ رحلتك التعليمية') ?></p><b>استكشف المسار ↗</b></div></a><?php endforeach; ?></div>
    </section>

    <section class="container v2-section v2-courses">
        <div class="v2-section-head"><div><span class="v2-overline">CURATED LEARNING</span><h2>دورات مصممة لتصنع فرقاً</h2></div><a href="courses.php">رؤية جميع الدورات <b>←</b></a></div>
        <div class="v2-course-row"><?php foreach ($courses as $c): ?><a href="course.php?id=<?= (int)$c['id'] ?>" class="v2-course"><div class="v2-course-image"><?php if (!empty($c['image'])): ?><img src="<?= SITE_URL ?>/uploads/courses/<?= rawurlencode($c['image']) ?>" alt="<?= clean($c['title']) ?>" loading="lazy"><?php else: ?><span class="course-fallback-icon">✦</span><?php endif; ?><span><?= clean($c['cat_name'] ?? 'دورة') ?></span></div><div class="v2-course-body"><h3><?= clean($c['title']) ?></h3><small>◷ <?= (int)$c['duration_hours'] ?> ساعة <i>•</i> <?= levelName($c['level']) ?></small><div><strong><?= formatPrice($c['price']) ?></strong><b>التفاصيل ←</b></div></div></a><?php endforeach; ?></div></section>

    <section class="container v2-section learning-steps-section">
        <div class="v2-section-head"><div><span class="v2-overline">YOUR NEXT MOVE</span><h2>رحلتك في NOVA تبدأ بثلاث خطوات</h2></div></div>
        <div class="steps-grid"><div class="step-item"><span>01</span><div><h3>حدد هدفك</h3><p>اختر المهارة أو المسار الذي يناسب طموحك الحالي.</p></div></div><div class="step-item"><span>02</span><div><h3>تعلم وطبّق</h3><p>شاهد المحتوى، اقرأ، وطبّق المفاهيم على أمثلة عملية.</p></div></div><div class="step-item"><span>03</span><div><h3>تقدم بثقة</h3><p>راجع تقدمك وابنِ ملفاً تعليمياً يعكس تطورك الحقيقي.</p></div></div></div>
    </section>

    <section class="container testimonials-section">
        <div class="testimonial-heading"><span class="v2-overline">LEARNER STORIES</span><h2>تجربة تترك أثراً</h2></div>
        <div class="testimonial-grid"><article><div class="quote-mark">“</div><p>المنصة مرتبة وواضحة، والأهم أنني أعرف دائماً ما هي الخطوة التالية في مساري.</p><footer><span class="avatar-dot">س</span><div><b>سارة محمد</b><small>متعلّمة تصميم</small></div><strong>★★★★★</strong></footer></article><article><div class="quote-mark">“</div><p>أحببت طريقة عرض الدورات والانتقال من الفكرة إلى التطبيق بدون تعقيد.</p><footer><span class="avatar-dot avatar-pink">ع</span><div><b>علي القاضي</b><small>متعلّم برمجة</small></div><strong>★★★★★</strong></footer></article></div>
    </section>

    <section class="container v2-cta"><div><span class="v2-overline">START YOUR JOURNEY</span><h2>المستقبل لا ينتظر.<br>ابدأ أنت.</h2><p>حوّل وقتك إلى معرفة، ومعرفتك إلى فرصة.</p></div><a href="register.php" class="btn btn-primary btn-lg">أنشئ حسابك مجاناً <b>←</b></a></section>
</div>
<?php require_once 'includes/footer.php'; ?>
