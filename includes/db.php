<?php
// حماية الملف
if (!defined('NOVA_APP')) {
    die('لا يمكن الوصول لهذا الملف مباشرة');
}

// الاتصال بقاعدة البيانات
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage());
}