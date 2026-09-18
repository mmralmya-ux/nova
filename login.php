<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/dashboard.php');
}

$errors = [];
$identifier_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $identifier_value = $identifier;
    
    if ($identifier === '') $errors['email'] = 'أدخل البريد الإلكتروني أو الاسم الكامل';
    if (empty($password)) $errors['password'] = 'كلمة المرور مطلوبة';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR full_name = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $errors['email'] = 'لا يوجد حساب بهذا البريد';
        } elseif (!$user['is_active']) {
            $errors['email'] = 'الحساب موقوف. تواصل مع الإدارة';
        } elseif (!password_verify($password, $user['password'])) {
            $errors['password'] = 'كلمة المرور غير صحيحة';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            setFlash('success', '👋 مرحباً بعودتك، ' . $user['full_name']);
            
            if ($user['role'] === 'admin') {
                redirect(SITE_URL . '/admin/index.php');
            } else {
                redirect(SITE_URL . '/dashboard.php');
            }
        }
    }
}

$pageTitle = 'تسجيل الدخول — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-head">
            <h1>تسجيل الدخول</h1>
            <p>أدخل بياناتك للمتابعة</p>
        </div>

        <form method="POST" novalidate>
            <div class="form-group <?= isset($errors['email']) ? 'error' : '' ?>">
                <label class="form-label">البريد الإلكتروني أو الاسم الكامل</label>
                <input type="text" name="email" class="form-input"
                       value="<?= htmlspecialchars($identifier_value) ?>" required>
                <div class="form-error"><?= $errors['email'] ?? '' ?></div>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'error' : '' ?>">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-input" required>
                <div class="form-error"><?= $errors['password'] ?? '' ?></div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">دخول</button>
        </form>

        <p style="text-align:center;margin-top:20px;color:var(--text-2)">
            ليس لديك حساب؟ <a href="register.php" style="font-weight:800">أنشئ حساباً جديداً</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
