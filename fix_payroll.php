<?php
$modulesDir = 'c:/xampp/htdocs/sch/modules';
$payrollDir = $modulesDir . '/payroll';

if (!is_dir($payrollDir)) {
    mkdir($payrollDir);
    echo "Created payroll directory via PHP\n";
}

$source = 'c:/xampp/htdocs/sch/modules/pr/PayrollModel.php';
$dest = $payrollDir . '/PayrollModel.php';

if (file_exists($source)) {
    if (rename($source, $dest)) {
        echo "Moved PayrollModel.php to $dest\n";
    } else {
        echo "Failed to move file\n";
    }
} else {
    echo "Source file NOT found: $source\n";
    // Try to find where it is via PHP
    $items = scandir($modulesDir);
    echo "Searching for pr in modules...\n";
    if (in_array('pr', $items)) {
        echo "pr FOUND in modules via scandir!\n";
    } else {
        echo "pr NOT found in modules via scandir.\n";
    }
}
?>
