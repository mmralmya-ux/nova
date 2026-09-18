<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// تغيير الحالة
if (isset($_GET['status']) && is_numeric($_GET['status']) && isset($_GET['to'])) {
    $id = (int)$_GET['status'];
    $to = $_GET['to'];
    if (in_array($to, ['pending', 'active', 'completed', 'cancelled'])) {
        $pdo->prepare("UPDATE enrollments SET status = ? WHERE id = ?")->execute([$to, $id]);
        setFlash('success', 'تم تحديث الحالة');
    }
    redirect(SITE_URL . '/admin/enrollments.php');
}

// الحذف
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM enrollments WHERE id = ?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'تم حذف التسجيل');
    redirect(SITE_URL . '/admin/enrollments.php');
}

$enrollments = $pdo->query("SELECT e.*, u.full_name AS user_name, u.email AS user_email, 
                                   c.title AS course_title, c.price AS course_price
                            FROM enrollments e
                            JOIN users u ON u.id = e.user_id
                            JOIN courses c ON c.id = e.course_id
                            ORDER BY e.enrolled_at DESC")->fetchAll();

$pageTitle = 'إدارة التسجيلات';
require_once 'includes/header.php';
?>

<div class="card">
    <h2 style="margin-bottom:18px">✍️ التسجيلات (<?= count($enrollments) ?>)</h2>
    
    <?php if (empty($enrollments)): ?>
        <div class="empty"><div class="icon">📋</div><p>لا توجد تسجيلات بعد</p></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>المستخدم</th>
                        <th>البريد</th>
                        <th>الدورة</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td><?= $e['id'] ?></td>
                            <td><strong><?= clean($e['user_name']) ?></strong></td>
                            <td dir="ltr" style="font-size:.85rem"><?= clean($e['user_email']) ?></td>
                            <td><?= clean($e['course_title']) ?></td>
                            <td><?= formatPrice($e['course_price']) ?></td>
                            <td>
                                <?php
                                $badge = 'badge-warning';
                                if ($e['status'] === 'active') $badge = 'badge-primary';
                                elseif ($e['status'] === 'completed') $badge = 'badge-success';
                                elseif ($e['status'] === 'cancelled') $badge = 'badge-danger';
                                ?>
                                <span class="badge <?= $badge ?>"><?= statusName($e['status']) ?></span>
                            </td>
                            <td><?= date('Y-m-d', strtotime($e['enrolled_at'])) ?></td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    <select onchange="location.href='?status=<?= $e['id'] ?>&to='+this.value" class="form-select" style="padding:6px 10px;font-size:.8rem;min-height:auto;width:auto">
                                        <option value="">تغيير الحالة</option>
                                        <option value="pending">قيد المراجعة</option>
                                        <option value="active">نشط</option>
                                        <option value="completed">مكتمل</option>
                                        <option value="cancelled">ملغي</option>
                                    </select>
                                    <a href="?delete=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('حذف التسجيل؟')">🗑</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>