<?php
// ⚠️ ملف مؤقت — سنحذفه بعد الاستخدام

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>معلومات المشرف</h2>";
echo "<p>البريد: <strong>admin@nova.com</strong></p>";
echo "<p>كلمة المرور: <strong>admin123</strong></p>";
echo "<hr>";
echo "<p>الهاش (انسخه):</p>";
echo "<textarea style='width:100%;height:80px;font-size:14px'>" . $hash . "</textarea>";