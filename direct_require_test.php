<?php
require_once 'c:/xampp/htdocs/sch/core/Model.php';
$file = 'c:/xampp/htdocs/sch/modules/payroll/PayrollModel.php';
if (file_exists($file)) {
    echo "File exists!\n";
    require_once $file;
    echo "Class exists: " . (class_exists('PayrollModel') ? "YES" : "NO") . "\n";
} else {
    echo "File NOT found via file_exists\n";
    echo "Trying with backslashes...\n";
    $file2 = 'c:\\xampp\\htdocs\\sch\\modules\\payroll\\PayrollModel.php';
    if (file_exists($file2)) {
        echo "Backslashes worked!\n";
    } else {
        echo "Nothing worked.\n";
    }
}
