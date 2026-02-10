<?php
require_once 'config/constants.php';
require_once 'config/database.php';

try {
    $db = getDbConnection();
    $expected = ['departments', 'employees', 'applicants', 'staff_contracts', 'leave_types', 'leave_requests', 'payroll_configs', 'payrolls'];
    
    echo "Checking for HR Tables:\n";
    foreach ($expected as $table) {
        $check = $db->query("SHOW TABLES LIKE '$table'")->fetch();
        echo ($check ? "[OK]" : "[MISSING]") . " $table\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
