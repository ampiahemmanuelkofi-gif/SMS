<?php
require_once 'config/constants.php';
require_once 'config/database.php';

try {
    $db = getDbConnection();
    echo "Connected to database.\n";
    
    $sql = file_get_contents('database/migrations/004_hr_management.sql');
    if (!$sql) die("Could not read migration file.\n");

    // Remove comments
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "Found " . count($statements) . " statements.\n";
    
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $db->exec($stmt);
                echo ".";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "s"; 
                } else {
                    echo "\n[ERROR]: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    echo "\nMigration finished.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
