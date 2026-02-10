<?php
require_once 'config/database.php';

$sqlFile = 'database/migrations/011_asset_inventory.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
$statements = preg_split("/;[\r\n]+/", $sql);

$db = getDbConnection();

echo "Starting Asset & Inventory Migration...\n";
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
