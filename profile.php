<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$user = currentUser();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($_POST['full_name'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $city = clean($_POST['city'] ?? '');
    $gender = $_POST['gender'] ?? 'male';
    $birthdate = $_POST['birthdate'] ?? null;
    $password = $_POST['password'] ?? '';
    
    if (mb_strlen($full_name) < 3) $errors['full_name'] = 'الاسم قصير جداً';
    
    if (empty($errors)) {
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors['password'] = 'كلمة المرور يجب أن تكون 8 أحرف';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET full_name=?, phone=?, city=?, gender=?, birthdate=?, password=? WHERE id=?");
                $stmt->execute([$full_name, $phone, $city, $gender, $birthdate ?: null, $hash, $user['id']]);
            }
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE users SET full_name=?, phone=?, city=?, gender=?, birthdate=? WHERE id=?");
            $stmt->execute([$full_name, $phone, $city, $gender, $birthdate ?: null, $user['id']]);
            
            $_SESSION['user_name'] = $full_name;
            setFlash('success', '✅ تم حفظ التغييرات بنجاح');
            redirect(SITE_URL . '/profile.php');
        }
    }
}

$pageTitle = 'الملف الشخصي — ' . SITE_NAME;
require_once 'includes/header.php';
?>

<div class="container">
    <div class="dash-grid">
        <aside class="dash-side">
            <h3 style="margin-bottom:14px;font-weight:900">حسابي</h3>
            <a href="dashboard.php">📊 نظرة عامة</a>
            <a href="my-courses.php">📚 دوراتي</a>
            <a href="courses.php">🔍 تصفح الدورات</a>
            <a href="profile.php" class="active">👤 الملف الشخصي</a>
            <a href="logout.php" style="color:var(--danger)">🚪 تسجيل الخروج</a>
        </aside>

        <div class="dash-content">
            <h2 style="margin-bottom:8px">👤 الملف الشخصي</h2>
            <p style="color:var(--text-2);margin-bottom:24px">عدّل بياناتك الشخصية</p>

            <form method="POST" style="max-width:560px">
                <div class="form-group <?= isset($errors['full_name']) ? 'error' : '' ?>">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="full_name" class="form-input" 
                           value="<?= clean($user['full_name']) ?>" required>
                    <div class="form-error"><?= $errors['full_name'] ?? '' ?></div>
                </div>

                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني (غير قابل للتعديل)</label>
                    <input type="email" class="form-input" value="<?= clean($user['email']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">رقم الجوال</label>
                    <input type="text" name="phone" class="form-input" dir="ltr"
                           value="<?= clean($user['phone']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">المدينة</label>
                    <input type="text" name="city" class="form-input" value="<?= clean($user['city']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" name="birthdate" class="form-input" value="<?= $user['birthdate'] ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">الجنس</label>
                    <div style="display:flex;gap:20px;padding:10px 0">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="radio" name="gender" value="male" <?= $user['gender'] === 'male' ? 'checked' : '' ?>> ذكر
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="radio" name="gender" value="female" <?= $user['gender'] === 'female' ? 'checked' : '' ?>> أنثى
                        </label>
                    </div>
                </div>

                <hr style="margin:24px 0;border:none;border-top:1px solid var(--border)">

                <div class="form-group <?= isset($errors['password']) ? 'error' : '' ?>">
                    <label class="form-label">كلمة مرور جديدة (اتركها فارغة للإبقاء على الحالية)</label>
                    <input type="password" name="password" class="form-input" placeholder="8 أحرف على الأقل">
                    <div class="form-error"><?= $errors['password'] ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">💾 حفظ التغييرات</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>