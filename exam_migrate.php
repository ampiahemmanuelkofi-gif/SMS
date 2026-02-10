<?php
require_once 'config/database.php';

$sqlFile = 'database/migrations/012_examination_grading.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
$statements = preg_split("/;[\r\n]+/", $sql);

$db = getDbConnection();

echo "Starting Exam & Grading Migration...\n";
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    
    try {
        echo "Executing statement...\n";
        $db->exec($stmt);
        echo "Success.\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
echo "Migration Complete.\n";
