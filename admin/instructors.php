<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM instructors WHERE id = ?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'تم حذف المدرب');
    redirect(SITE_URL . '/admin/instructors.php');
}

$editing = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM instructors WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($_POST['full_name'] ?? '');
    $specialty = clean($_POST['specialty'] ?? '');
    $bio = clean($_POST['bio'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $id = (int)($_POST['id'] ?? 0);
    
    if ($full_name !== '' && $specialty !== '') {
        if ($id > 0) {
            $pdo->prepare("UPDATE instructors SET full_name=?, specialty=?, bio=?, email=? WHERE id=?")
                ->execute([$full_name, $specialty, $bio, $email, $id]);
            setFlash('success', 'تم تحديث المدرب');
        } else {
            $pdo->prepare("INSERT INTO instructors (full_name, specialty, bio, email) VALUES (?, ?, ?, ?)")
                ->execute([$full_name, $specialty, $bio, $email]);
            setFlash('success', 'تم إضافة المدرب');
        }
        redirect(SITE_URL . '/admin/instructors.php');
    }
}

$instructors = $pdo->query("SELECT * FROM instructors ORDER BY id DESC")->fetchAll();

$pageTitle = 'إدارة المدربين';
require_once 'includes/header.php';
?>

<div class="card" style="margin-bottom:24px">
    <h2 style="margin-bottom:18px"><?= $editing ? '✏️ تعديل مدرب' : '➕ إضافة مدرب' ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? 0 ?>">
        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">الاسم *</label>
                <input type="text" name="full_name" class="form-input" value="<?= $editing['full_name'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">التخصص *</label>
                <input type="text" name="specialty" class="form-input" value="<?= $editing['specialty'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">البريد</label>
                <input type="email" name="email" class="form-input" dir="ltr" value="<?= $editing['email'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">نبذة</label>
                <input type="text" name="bio" class="form-input" value="<?= $editing['bio'] ?? '' ?>">
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">💾 <?= $editing ? 'حفظ' : 'إضافة' ?></button>
            <?php if ($editing): ?><a href="instructors.php" class="btn btn-outline">إلغاء</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom:18px">👨‍🏫 المدربون (<?= count($instructors) ?>)</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>الاسم</th><th>التخصص</th><th>البريد</th><th>نبذة</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $i): ?>
                    <tr>
                        <td><?= $i['id'] ?></td>
                        <td><strong><?= clean($i['full_name']) ?></strong></td>
                        <td><?= clean($i['specialty']) ?></td>
                        <td dir="ltr"><?= clean($i['email'] ?: '-') ?></td>
                        <td><?= clean($i['bio'] ?: '-') ?></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="?edit=<?= $i['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                                <a href="?delete=<?= $i['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">🗑</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>