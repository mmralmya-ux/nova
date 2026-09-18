<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$errors = [];
$success = false;
$old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');
    
    $old = compact('name', 'email', 'subject', 'message');
    
    if (mb_strlen($name) < 3) $errors['name'] = 'الاسم مطلوب';
    if (!isValidEmail($email)) $errors['email'] = 'البريد غير صحيح';
    if (empty($subject)) $errors['subject'] = 'الموضوع مطلوب';
    if (mb_strlen($message) < 10) $errors['message'] = 'الرسالة قصيرة جداً';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        
        setFlash('success', '✅ تم إرسال رسالتك بنجاح! سنتواصل معك قريباً');
        redirect(SITE_URL . '/contact.php');
    }
}

$pageTitle = 'تواصل معنا — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <h1 style="font-size:2rem;font-weight:900;margin-bottom:8px">📞 تواصل معنا</h1>
    <p style="color:var(--text-2);margin-bottom:30px">هل لديك سؤال؟ نحن هنا للمساعدة</p>

    <div class="grid grid-2">
        <!-- معلومات التواصل -->
        <div>
            <div class="card" style="margin-bottom:16px">
                <div style="font-size:2rem;margin-bottom:10px">📧</div>
                <h3 style="font-weight:800">البريد الإلكتروني</h3>
                <p style="color:var(--text-2);margin-top:6px"><?= SITE_EMAIL ?></p>
            </div>
            <div class="card" style="margin-bottom:16px">
                <div style="font-size:2rem;margin-bottom:10px">📱</div>
                <h3 style="font-weight:800">الهاتف</h3>
                <p style="color:var(--text-2);margin-top:6px" dir="ltr">+967 700 000 000</p>
            </div>
            <div class="card">
                <div style="font-size:2rem;margin-bottom:10px">📍</div>
                <h3 style="font-weight:800">العنوان</h3>
                <p style="color:var(--text-2);margin-top:6px">صنعاء، اليمن</p>
            </div>
        </div>

        <!-- نموذج التواصل -->
        <div class="card">
            <h2 style="font-weight:900;margin-bottom:20px">أرسل رسالة</h2>

            <form method="POST" novalidate>
                <div class="form-group <?= isset($errors['name']) ? 'error' : '' ?>">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="name" class="form-input" value="<?= $old['name'] ?>" required>
                    <div class="form-error"><?= $errors['name'] ?? '' ?></div>
                </div>

                <div class="form-group <?= isset($errors['email']) ? 'error' : '' ?>">
                    <label class="form-label">البريد الإلكتروني *</label>
                    <input type="email" name="email" class="form-input" dir="ltr" value="<?= $old['email'] ?>" required>
                    <div class="form-error"><?= $errors['email'] ?? '' ?></div>
                </div>

                <div class="form-group <?= isset($errors['subject']) ? 'error' : '' ?>">
                    <label class="form-label">الموضوع *</label>
                    <input type="text" name="subject" class="form-input" value="<?= $old['subject'] ?>" required>
                    <div class="form-error"><?= $errors['subject'] ?? '' ?></div>
                </div>

                <div class="form-group <?= isset($errors['message']) ? 'error' : '' ?>">
                    <label class="form-label">الرسالة *</label>
                    <textarea name="message" class="form-textarea" required><?= $old['message'] ?></textarea>
                    <div class="form-error"><?= $errors['message'] ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">📤 إرسال</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>