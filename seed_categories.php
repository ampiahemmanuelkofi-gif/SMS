<?php
require_once 'config/database.php';
$db = getDbConnection();
try {
    $db->prepare("INSERT INTO student_categories (name, description) VALUES (?, ?)")->execute(['Day Student', 'Non-boarding student']);
    $db->prepare("INSERT INTO student_categories (name, description) VALUES (?, ?)")->execute(['Boarder', 'Student residing in school hostel']);
    $db->prepare("INSERT INTO student_categories (name, description) VALUES (?, ?)")->execute(['Scholarship', 'Student receiving financial aid']);
    $db->prepare("INSERT INTO student_categories (name, description) VALUES (?, ?)")->execute(['Special Needs', 'Student requiring additional support']);
    echo "Seed successful.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
