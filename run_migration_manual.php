<?php
// Load environment
require_once 'config/constants.php';
require_once 'config/database.php';

try {
    $db = getDbConnection();
    echo "Connected to database.\n";
    
    $migrationsDir = 'database/migrations';
    if (!is_dir($migrationsDir)) {
        die("Migrations directory not found: $migrationsDir\n");
    }
    
    $files = scandir($migrationsDir);
    sort($files);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'sql') continue;
        
        $sqlFile = $migrationsDir . '/' . $file;
        echo "Found migration: $file\n";
        
        $sql = file_get_contents($sqlFile);
        if ($sql === false) {
            echo "FAILED to read: $sqlFile\n";
            continue;
        }
        echo "Read " . strlen($sql) . " bytes from $file\n";

        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by semicolon
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        echo "Extracted " . count($statements) . " statements\n";
        
        $count = 0;
        foreach ($statements as $stmt) {
            if (!empty($stmt)) {
                try {
                    $db->exec($stmt);
                    $count++;
                } catch (PDOException $e) {
                    // Ignore "Table already exists" or "Duplicate column" if re-running
                    if (strpos($e->getMessage(), 'already exists') !== false || 
                        strpos($e->getMessage(), 'Duplicate column') !== false ||
                        strpos($e->getMessage(), 'Duplicate key') !== false) {
                        $count++;
                        continue;
                    }
                    echo "\n[ERROR] in statement: " . substr($stmt, 0, 100) . "...\n";
                    echo "Message: " . $e->getMessage() . "\n";
                }
            }
        }
        echo "Done ($count statements).\n";
    }
    
    echo "All migrations processed.\n";
    
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}
