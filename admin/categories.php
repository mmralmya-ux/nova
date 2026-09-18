<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'تم حذف التصنيف');
    redirect(SITE_URL . '/admin/categories.php');
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $description = clean($_POST['description'] ?? '');
    $icon = clean($_POST['icon'] ?? '📚');
    $id = (int)($_POST['id'] ?? 0);
    
    if ($name !== '') {
        if ($id > 0) {
            $pdo->prepare("UPDATE categories SET name=?, description=?, icon=? WHERE id=?")
                ->execute([$name, $description, $icon, $id]);
            setFlash('success', 'تم تحديث التصنيف');
        } else {
            $pdo->prepare("INSERT INTO categories (name, description, icon) VALUES (?, ?, ?)")
                ->execute([$name, $description, $icon]);
            setFlash('success', 'تم إضافة التصنيف');
        }
        redirect(SITE_URL . '/admin/categories.php');
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();

$pageTitle = 'إدارة التصنيفات';
require_once 'includes/header.php';
?>

<div class="card" style="margin-bottom:24px">
    <h2 style="margin-bottom:18px"><?= $editing ? '✏️ تعديل تصنيف' : '➕ إضافة تصنيف' ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? 0 ?>">
        <div class="grid grid-3">
            <div class="form-group">
                <label class="form-label">الاسم *</label>
                <input type="text" name="name" class="form-input" value="<?= $editing['name'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">الأيقونة</label>
                <input type="text" name="icon" class="form-input" value="<?= $editing['icon'] ?? '📚' ?>" maxlength="4">
            </div>
            <div class="form-group">
                <label class="form-label">الوصف</label>
                <input type="text" name="description" class="form-input" value="<?= $editing['description'] ?? '' ?>">
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 <?= $editing ? 'حفظ' : 'إضافة' ?></button>
            <?php if ($editing): ?><a href="categories.php" class="btn btn-outline">إلغاء</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom:18px">📁 التصنيفات (<?= count($categories) ?>)</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>الأيقونة</th><th>الاسم</th><th>الوصف</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td style="font-size:1.5rem"><?= $c['icon'] ?></td>
                        <td><strong><?= clean($c['name']) ?></strong></td>
                        <td><?= clean($c['description'] ?? '-') ?></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="?edit=<?= $c['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                                <a href="?delete=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('حذف التصنيف؟ سيُحذف كل دوراته')">🗑</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>