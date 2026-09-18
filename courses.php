<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// الفلترة
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? clean($_GET['search']) : '';

// استعلام
$sql = "SELECT c.*, cat.name AS cat_name, i.full_name AS instructor_name 
        FROM courses c 
        LEFT JOIN categories cat ON cat.id = c.category_id 
        LEFT JOIN instructors i ON i.id = c.instructor_id 
        WHERE c.is_active = 1";
$params = [];

if ($category > 0) {
    $sql .= " AND c.category_id = ?";
    $params[] = $category;
}

if ($search !== '') {
    $sql .= " AND c.title LIKE ?";
    $params[] = "%$search%";
}

$sql .= " ORDER BY c.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

// التصنيفات
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$pageTitle = 'الدورات — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container courses-page">
    <div class="page-heading"><div><span class="eyebrow">NOVA ACADEMY</span><h1>الدورات التدريبية</h1><p>اكتشف مسارك القادم وتعلم بمرونة مع دورات عملية.</p></div><div class="result-count"><strong><?= count($courses) ?></strong><span>دورة متاحة</span></div></div>

    <!-- الفلاتر -->
    <div class="course-toolbar">
        <form method="GET" class="course-filter-form">
            <div class="search-field">
                <label class="form-label" for="course-search">ابحث عن دورة</label>
                <span class="search-icon">⌕</span>
                <input id="course-search" type="search" name="search" class="form-input" value="<?= htmlspecialchars($search) ?>" placeholder="مثال: تطوير الويب، UX...">
            </div>
            <div class="category-field">
                <label class="form-label" for="course-category">التصنيف</label>
                <select id="course-category" name="category" class="form-select">
                    <option value="0">كل التصنيفات</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
                            <?= $cat['icon'] ?> <?= clean($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-actions"><button type="submit" class="btn btn-primary">بحث عن دورة</button><a href="courses.php" class="btn btn-outline">إعادة ضبط</a></div>
        </form>
    </div>

    <!-- الدورات -->
    <?php if (empty($courses)): ?>
        <div class="empty">
            <div class="icon">🔍</div>
            <h3>لا توجد نتائج</h3>
            <p>جرب تغيير الفلاتر</p>
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
                        <?php if ($c['instructor_name']): ?>
                            <div style="font-size:.83rem;color:var(--muted)">👨‍🏫 <?= clean($c['instructor_name']) ?></div>
                        <?php endif; ?>
                        <div class="course-price"><?= formatPrice($c['price']) ?></div>
                        <a href="course.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-block">التفاصيل والتسجيل</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
