<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// إنهاء الجلسة
session_unset();
session_destroy();

// إعادة تشغيل جلسة جديدة لرسالة التنبيه
session_start();
setFlash('info', '👋 تم تسجيل خروجك بنجاح');

redirect(SITE_URL . '/login.php');