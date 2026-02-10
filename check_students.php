<?php
require_once 'config/database.php';
$db = getDbConnection();
$students = $db->query("SELECT id, first_name, last_name, student_id FROM students LIMIT 10")->fetchAll();
echo "Total Students Found: " . count($students) . "\n";
foreach ($students as $s) {
    echo "ID: {$s['id']} | Name: {$s['first_name']} {$s['last_name']} | Code: {$s['student_id']}\n";
}

echo "\n--- All Tables ---\n";
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    echo "$t\n";
}
