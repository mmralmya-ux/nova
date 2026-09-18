<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// الحذف
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM courses WHERE id = ?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'تم حذف الدورة');
    redirect(SITE_URL . '/admin/courses.php');
}

// إضافة أو تعديل
$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean($_POST['title'] ?? '');
    $description = clean($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $instructor_id = (int)($_POST['instructor_id'] ?? 0) ?: null;
    $price = (float)($_POST['price'] ?? 0);
    $duration_hours = (int)($_POST['duration_hours'] ?? 0);
    $level = $_POST['level'] ?? 'beginner';
    $seats = (int)($_POST['seats'] ?? 30);
    $id = (int)($_POST['id'] ?? 0);
    
    if (empty($title)) $errors[] = 'العنوان مطلوب';
    if (empty($description)) $errors[] = 'الوصف مطلوب';
    if ($category_id <= 0) $errors[] = 'التصنيف مطلوب';
    
    if (empty($errors)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE courses SET title=?, description=?, category_id=?, instructor_id=?, price=?, duration_hours=?, level=?, seats=? WHERE id=?");
            $stmt->execute([$title, $description, $category_id, $instructor_id, $price, $duration_hours, $level, $seats, $id]);
            setFlash('success', 'تم تحديث الدورة');
        } else {
            $stmt = $pdo->prepare("INSERT INTO courses (title, description, category_id, instructor_id, price, duration_hours, level, seats) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $category_id, $instructor_id, $price, $duration_hours, $level, $seats]);
            setFlash('success', 'تم إضافة الدورة');
        }
        redirect(SITE_URL . '/admin/courses.php');
    }
}

$courses = $pdo->query("SELECT c.*, cat.name AS cat_name, i.full_name AS instructor_name 
                       FROM courses c 
                       LEFT JOIN categories cat ON cat.id = c.category_id
                       LEFT JOIN instructors i ON i.id = c.instructor_id
                       ORDER BY c.id DESC")->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$instructors = $pdo->query("SELECT * FROM instructors")->fetchAll();

$pageTitle = 'إدارة الدورات';
require_once 'includes/header.php';
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <div><strong>أخطاء:</strong><br><?= implode('<br>', $errors) ?></div>
    </div>
<?php endif; ?>

<div class="card" style="margin-bottom:24px">
    <h2 style="margin-bottom:18px"><?= $editing ? '✏️ تعديل دورة' : '➕ إضافة دورة جديدة' ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? 0 ?>">
        
        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">العنوان *</label>
                <input type="text" name="title" class="form-input" value="<?= $editing['title'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">التصنيف *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">اختر تصنيفاً</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($editing['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= $c['icon'] ?> <?= clean($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">المدرب</label>
                <select name="instructor_id" class="form-select">
                    <option value="">بدون مدرب</option>
                    <?php foreach ($instructors as $i): ?>
                        <option value="<?= $i['id'] ?>" <?= ($editing['instructor_id'] ?? '') == $i['id'] ? 'selected' : '' ?>>
                            <?= clean($i['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">السعر (ر.س)</label>
                <input type="number" name="price" class="form-input" step="0.01" value="<?= $editing['price'] ?? '0' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">المدة (ساعة)</label>
                <input type="number" name="duration_hours" class="form-input" value="<?= $editing['duration_hours'] ?? '0' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">المستوى</label>
                <select name="level" class="form-select">
                    <option value="beginner" <?= ($editing['level'] ?? '') === 'beginner' ? 'selected' : '' ?>>مبتدئ</option>
                    <option value="intermediate" <?= ($editing['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>متوسط</option>
                    <option value="advanced" <?= ($editing['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>متقدم</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">عدد المقاعد</label>
                <input type="number" name="seats" class="form-input" value="<?= $editing['seats'] ?? '30' ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">الوصف *</label>
            <textarea name="description" class="form-textarea" required><?= $editing['description'] ?? '' ?></textarea>
        </div>
        
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 <?= $editing ? 'حفظ التعديلات' : 'إضافة' ?></button>
            <?php if ($editing): ?>
                <a href="courses.php" class="btn btn-outline">إلغاء</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom:18px">📚 قائمة الدورات (<?= count($courses) ?>)</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>العنوان</th>
                    <th>التصنيف</th>
                    <th>المدرب</th>
                    <th>السعر</th>
                    <th>المستوى</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><strong><?= clean($c['title']) ?></strong></td>
                        <td><?= clean($c['cat_name'] ?? '-') ?></td>
                        <td><?= clean($c['instructor_name'] ?? '-') ?></td>
                        <td><?= formatPrice($c['price']) ?></td>
                        <td><?= levelName($c['level']) ?></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="?edit=<?= $c['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                                <a href="?delete=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">🗑</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>