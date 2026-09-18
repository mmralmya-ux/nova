<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// لو مسجل دخول → للوحة التحكم
if (isLoggedIn()) {
    redirect(SITE_URL . '/dashboard.php');
}

$errors = [];
$old = ['full_name' => '', 'email' => '', 'phone' => '', 'city' => '', 'gender' => 'male'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($_POST['full_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $city = clean($_POST['city'] ?? '');
    $gender = $_POST['gender'] ?? 'male';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    $old = compact('full_name', 'email', 'phone', 'city', 'gender');
    
    // التحقق
    if (mb_strlen($full_name) < 3) $errors['full_name'] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
    if (!isValidEmail($email)) $errors['email'] = 'البريد الإلكتروني غير صحيح';
    if (strlen($password) < 8) $errors['password'] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
    if ($password !== $confirm) $errors['confirm_password'] = 'كلمتا المرور غير متطابقتين';
    if (!in_array($gender, ['male', 'female'])) $gender = 'male';
    
    // التحقق من البريد المكرر
    if (empty($errors['email'])) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'هذا البريد مسجّل مسبقاً';
        }
    }
    
    // الحفظ
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, city, gender) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $hash, $phone, $city, $gender]);
        
        setFlash('success', '🎉 تم إنشاء حسابك بنجاح! سجّل دخولك الآن');
        redirect(SITE_URL . '/login.php');
    }
}

$pageTitle = 'إنشاء حساب — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-head">
            <h1>إنشاء حساب جديد</h1>
            <p>انضم إلينا وابدأ رحلتك التعليمية</p>
        </div>

        <form method="POST" novalidate>
            <div class="form-group <?= isset($errors['full_name']) ? 'error' : '' ?>">
                <label class="form-label">الاسم الكامل *</label>
                <input type="text" name="full_name" class="form-input" 
                       value="<?= htmlspecialchars($old['full_name']) ?>" required>
                <div class="form-error"><?= $errors['full_name'] ?? '' ?></div>
            </div>

            <div class="form-group <?= isset($errors['email']) ? 'error' : '' ?>">
                <label class="form-label">البريد الإلكتروني *</label>
                <input type="email" name="email" class="form-input" dir="ltr"
                       value="<?= htmlspecialchars($old['email']) ?>" required>
                <div class="form-error"><?= $errors['email'] ?? '' ?></div>
            </div>

            <div class="form-group">
                <label class="form-label">رقم الجوال</label>
                <input type="text" name="phone" class="form-input" dir="ltr"
                       value="<?= htmlspecialchars($old['phone']) ?>" placeholder="+967 7XX XXX XXX">
            </div>

            <div class="form-group">
                <label class="form-label">المدينة</label>
                <input type="text" name="city" class="form-input"
                       value="<?= htmlspecialchars($old['city']) ?>" placeholder="صنعاء">
            </div>

            <div class="form-group">
                <label class="form-label">الجنس</label>
                <div style="display:flex;gap:20px;padding:10px 0">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="radio" name="gender" value="male" 
                               <?= $old['gender'] === 'male' ? 'checked' : '' ?>> ذكر
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="radio" name="gender" value="female"
                               <?= $old['gender'] === 'female' ? 'checked' : '' ?>> أنثى
                    </label>
                </div>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'error' : '' ?>">
                <label class="form-label">كلمة المرور *</label>
                <input type="password" name="password" class="form-input" 
                       placeholder="8 أحرف على الأقل" required>
                <div class="form-error"><?= $errors['password'] ?? '' ?></div>
            </div>

            <div class="form-group <?= isset($errors['confirm_password']) ? 'error' : '' ?>">
                <label class="form-label">تأكيد كلمة المرور *</label>
                <input type="password" name="confirm_password" class="form-input" required>
                <div class="form-error"><?= $errors['confirm_password'] ?? '' ?></div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">إنشاء الحساب</button>
        </form>

        <p style="text-align:center;margin-top:20px;color:var(--text-2)">
            لديك حساب؟ <a href="login.php" style="font-weight:800">سجّل دخولك</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>