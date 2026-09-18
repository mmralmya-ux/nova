<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// الحذف
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) { // لا تحذف نفسك
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        setFlash('success', 'تم حذف المستخدم');
    } else {
        setFlash('error', 'لا يمكنك حذف حسابك');
    }
    redirect(SITE_URL . '/admin/users.php');
}

// تغيير الحالة
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?")->execute([$id]);
    setFlash('success', 'تم تغيير حالة الحساب');
    redirect(SITE_URL . '/admin/users.php');
}

// تغيير الصلاحية
if (isset($_GET['role']) && is_numeric($_GET['role'])) {
    $id = (int)$_GET['role'];
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $currentRole = $stmt->fetchColumn();
    $newRole = $currentRole === 'admin' ? 'user' : 'admin';
    $pdo->prepare("UPDATE users SET role = ? WHERE id = ?")->execute([$newRole, $id]);
    setFlash('success', 'تم تغيير الصلاحية');
    redirect(SITE_URL . '/admin/users.php');
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

$pageTitle = 'إدارة المستخدمين';
require_once 'includes/header.php';
?>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
        <h2>👥 المستخدمون (<?= count($users) ?>)</h2>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>الجوال</th>
                    <th>الصلاحية</th>
                    <th>الحالة</th>
                    <th>تاريخ التسجيل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><strong><?= clean($u['full_name']) ?></strong></td>
                        <td dir="ltr"><?= clean($u['email']) ?></td>
                        <td dir="ltr"><?= clean($u['phone'] ?: '-') ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' ? 'badge-danger' : 'badge-primary' ?>">
                                <?= $u['role'] === 'admin' ? 'مشرف' : 'مستخدم' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $u['is_active'] ? 'badge-success' : 'badge-warning' ?>">
                                <?= $u['is_active'] ? 'مفعّل' : 'موقوف' ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                <a href="?role=<?= $u['id'] ?>" class="btn btn-outline btn-sm" title="تغيير الصلاحية">🔄</a>
                                <a href="?toggle=<?= $u['id'] ?>" class="btn btn-warning btn-sm" title="تفعيل/إيقاف"><?= $u['is_active'] ? '⏸' : '▶' ?></a>
                                <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                    <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')" title="حذف">🗑</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>