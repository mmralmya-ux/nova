<?php
// ═══════════════════════════════════════════
// إعدادات موقع معهد NOVA
// ═══════════════════════════════════════════

// حماية الملف
if (!defined('NOVA_APP')){
    define('NOVA_APP', true);
}

// ─────────────────────────────────────────
// إعدادات قاعدة البيانات
// ─────────────────────────────────────────
define('DB_HOST', getenv('NOVA_DB_HOST') ?: 'localhost');
define('DB_USER', getenv('NOVA_DB_USER') ?: 'root');
define('DB_PASS', getenv('NOVA_DB_PASS') ?: '');
define('DB_NAME', getenv('NOVA_DB_NAME') ?: 'nova_db');

// ─────────────────────────────────────────
// إعدادات الموقع
// ─────────────────────────────────────────
define('SITE_NAME', 'معهد NOVA التدريبي');
define('SITE_URL', getenv('NOVA_SITE_URL') ?: 'https://novaeslz.duckdns.org');
define('SITE_EMAIL', 'info@nova.com');

// ─────────────────────────────────────────
// المنطقة الزمنية
// ─────────────────────────────────────────
date_default_timezone_set('Asia/Riyadh');

// ─────────────────────────────────────────
// تشغيل الجلسة
// ─────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ─────────────────────────────────────────
// إظهار الأخطاء (للتطوير فقط)
// ─────────────────────────────────────────
if (getenv('NOVA_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}
