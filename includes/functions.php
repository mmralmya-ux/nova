<?php
if (!defined('NOVA_APP')) die('Access denied');

// تنظيف المدخلات (يمنع XSS)
function clean($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// التحقق من البريد
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// رسائل التنبيه (Flash Messages)
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// إعادة التوجيه
function redirect($url) {
    header("Location: $url");
    exit;
}

// رفع الصور
function uploadImage($file, $targetDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 2 * 1024 * 1024) return null;
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed)) return null;
    
    $filename = uniqid('img_') . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $targetDir . $filename)) return null;
    return $filename;
}

function uploadVideo($file, $targetDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 50 * 1024 * 1024) return null;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['mp4', 'webm', 'ogg'];
    if (!in_array($ext, $allowed, true)) return null;
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $targetDir . $filename)) return null;
    return $filename;
}

// تنسيق السعر
function formatPrice($price) {
    return number_format($price, 2) . ' ر.س';
}

// اسم المستوى بالعربي
function levelName($level) {
    $levels = [
        'beginner' => 'مبتدئ',
        'intermediate' => 'متوسط',
        'advanced' => 'متقدم'
    ];
    return $levels[$level] ?? $level;
}

// اسم حالة التسجيل
function statusName($status) {
    $statuses = [
        'pending' => 'قيد المراجعة',
        'active' => 'نشط',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي'
    ];
    return $statuses[$status] ?? $status;
}
