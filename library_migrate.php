<?php
require_once 'config/database.php';

$sqlFile = 'database/migrations/005_library_management.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
// Split by semicolon, but handle cases where semicolon is inside a string or comment if possible
// For this specific file, simple splitting on semicolon followed by newline is usually safe enough
$statements = preg_split("/;[\r\n]+/", $sql);

$db = getDbConnection();

echo "Starting Library Migration...\n";
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    
    try {
        echo "Executing: " . substr($stmt, 0, 50) . "...\n";
        $db->exec($stmt);
        echo "Success.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "already exists") !== false) {
            echo "Skipped (already exists).\n";
        } else {
            echo "Error: " . $e->getMessage() . "\n";
            // Continue with next statement
        }
    }
}
echo "Library Migration Complete.\n";
