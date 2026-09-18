<?php
define('NOVA_APP', true);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// حذف
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'تم حذف الرسالة');
    redirect(SITE_URL . '/admin/messages.php');
}

// تعليم كمقروءة
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?")->execute([(int)$_GET['read']]);
    redirect(SITE_URL . '/admin/messages.php');
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY is_read ASC, created_at DESC")->fetchAll();

$pageTitle = 'الرسائل';
require_once 'includes/header.php';
?>

<div class="card">
    <h2 style="margin-bottom:18px">📧 الرسائل (<?= count($messages) ?>)</h2>

    <?php if (empty($messages)): ?>
        <div class="empty"><div class="icon">📭</div><p>لا توجد رسائل</p></div>
    <?php else: ?>
        <?php foreach ($messages as $m): ?>
            <div class="card" style="margin-bottom:16px;border-right:4px solid <?= $m['is_read'] ? 'var(--border)' : 'var(--primary)' ?>">
                <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:12px;margin-bottom:12px">
                    <div>
                        <strong style="font-size:1.05rem"><?= clean($m['subject']) ?></strong>
                        <?php if (!$m['is_read']): ?>
                            <span class="badge badge-danger" style="margin-right:8px">جديدة</span>
                        <?php endif; ?>
                    </div>
                    <small style="color:var(--muted)"><?= date('Y-m-d H:i', strtotime($m['created_at'])) ?></small>
                </div>
                
                <div style="font-size:.88rem;color:var(--text-2);margin-bottom:10px">
                    <strong>من:</strong> <?= clean($m['name']) ?> — <span dir="ltr"><?= clean($m['email']) ?></span>
                </div>
                
                <p style="color:var(--text-2);line-height:1.9;margin-bottom:14px"><?= nl2br(clean($m['message'])) ?></p>
                
                <div style="display:flex;gap:8px">
                    <?php if (!$m['is_read']): ?>
                        <a href="?read=<?= $m['id'] ?>" class="btn btn-primary btn-sm">✓ تعليم كمقروءة</a>
                    <?php endif; ?>
                    <a href="mailto:<?= clean($m['email']) ?>" class="btn btn-outline btn-sm">↩️ رد</a>
                    <a href="?delete=<?= $m['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('حذف الرسالة؟')">🗑 حذف</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>