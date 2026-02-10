<?php
$modulesDir = 'c:/xampp/htdocs/sch/modules';
$payrollDir = $modulesDir . '/payroll';

if (!is_dir($payrollDir)) {
    mkdir($payrollDir);
    echo "Created payroll directory via PHP\n";
}

$source = 'c:/xampp/htdocs/sch/modules/hr/PayrollModel.php';
$dest = $payrollDir . '/PayrollModel.php';

if (file_exists($source)) {
    if (rename($source, $dest)) {
        echo "Successfully moved PayrollModel.php to $dest\n";
    } else {
        echo "Failed to move file via rename()\n";
    }
} else {
    echo "Source NOT found: $source\n";
}
?>
