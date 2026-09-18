<?php
define('NOVA_APP', true);
require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<h1 style='font-family:sans-serif;color:green'>✅ الاتصال يعمل!</h1>";
echo "<p style='font-family:sans-serif'>قاعدة البيانات: nova_db</p>";

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll();
echo "<p style='font-family:sans-serif'>عدد الجداول: <strong>" . count($tables) . "</strong></p>";
echo "<ul style='font-family:sans-serif'>";
foreach ($tables as $t) {
    echo "<li>" . array_values($t)[0] . "</li>";
}
echo "</ul>";