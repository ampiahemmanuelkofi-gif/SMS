<?php
require_once 'config/database.php';

$sqlFile = 'database/migrations/015_fix_student_associations.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
// Split by semicolon and new line to handle statements
$statements = preg_split("/;[\r\n]+/", $sql);

$db = getDbConnection();

echo "Starting Fix Migration...\n";
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    
    try {
        echo "Executing statement...\n";
        $db->exec($stmt);
        echo "Success.\n";
    } catch (Exception $e) {
        echo "Note: " . $e->getMessage() . "\n";
    }
}
echo "Migration Complete.\n";
