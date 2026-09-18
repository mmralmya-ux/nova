<?php
if (!defined('NOVA_APP')) die('Access denied');

// هل المستخدم مسجل دخول؟
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// هل المستخدم مشرف؟
function isAdmin() {
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
}

// طلب تسجيل الدخول
function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('error', 'يجب تسجيل الدخول أولاً');
        redirect(SITE_URL . '/login.php');
    }
}

// طلب صلاحية مشرف
function requireAdmin() {
    if (!isAdmin()) {
        setFlash('error', 'ليس لديك صلاحية');
        redirect(SITE_URL . '/login.php');
    }
}

// بيانات المستخدم الحالي
function currentUser() {
    if (!isLoggedIn()) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_active = 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}