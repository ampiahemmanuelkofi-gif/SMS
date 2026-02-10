<?php
/**
 * Application Constants
 */

// School Information
define('SCHOOL_NAME', 'Ghana Model School');
define('SCHOOL_MOTTO', 'Excellence in Education');
define('SCHOOL_ADDRESS', 'Accra, Ghana');
define('SCHOOL_PHONE', '+233 000 000 000');
define('SCHOOL_EMAIL', 'info@ghanamodelschool.edu.gh');

// Academic Levels
define('LEVELS', [
    'nursery' => 'Nursery',
    'primary' => 'Primary',
    'jhs' => 'Junior High School'
]);

// Classes by Level
define('CLASSES', [
    'nursery' => ['Nursery 1', 'Nursery 2'],
    'primary' => ['Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6'],
    'jhs' => ['JHS 1', 'JHS 2', 'JHS 3']
]);

// BECE Grading Scale (Ghana WAEC Standard)
define('BECE_GRADES', [
    ['min' => 75, 'max' => 100, 'grade' => 'A1', 'remark' => 'Excellent', 'points' => 1],
    ['min' => 70, 'max' => 74, 'grade' => 'B2', 'remark' => 'Very Good', 'points' => 2],
    ['min' => 65, 'max' => 69, 'grade' => 'B3', 'remark' => 'Good', 'points' => 3],
    ['min' => 60, 'max' => 64, 'grade' => 'C4', 'remark' => 'Credit', 'points' => 4],
    ['min' => 55, 'max' => 59, 'grade' => 'C5', 'remark' => 'Credit', 'points' => 5],
    ['min' => 50, 'max' => 54, 'grade' => 'C6', 'remark' => 'Credit', 'points' => 6],
    ['min' => 45, 'max' => 49, 'grade' => 'D7', 'remark' => 'Pass', 'points' => 7],
    ['min' => 40, 'max' => 44, 'grade' => 'E8', 'remark' => 'Pass', 'points' => 8],
    ['min' => 0, 'max' => 39, 'grade' => 'F9', 'remark' => 'Fail', 'points' => 9]
]);

// User Roles
define('ROLES', [
    'super_admin' => 'Super Administrator',
    'admin' => 'Administrator',
    'teacher' => 'Teacher',
    'accountant' => 'Accountant',
    'parent' => 'Parent'
]);

// Attendance Status
define('ATTENDANCE_STATUS', [
    'present' => 'Present',
    'absent' => 'Absent',
    'late' => 'Late',
    'excused' => 'Excused'
]);

// Payment Methods
define('PAYMENT_METHODS', [
    'cash' => 'Cash',
    'mobile_money' => 'Mobile Money',
    'bank_transfer' => 'Bank Transfer',
    'cheque' => 'Cheque'
]);

// Student Status
define('STUDENT_STATUS', [
    'active' => 'Active',
    'graduated' => 'Graduated',
    'transferred' => 'Transferred',
    'suspended' => 'Suspended'
]);

// Assessment Types
define('ASSESSMENT_TYPES', [
    'classwork' => 'Class Work',
    'homework' => 'Homework',
    'midterm' => 'Mid-Term Exam',
    'final' => 'Final Exam',
    'mock_bece' => 'Mock BECE'
]);

// Terms
define('TERMS', [
    '1st Term',
    '2nd Term',
    '3rd Term'
]);

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx']);

// Pagination
define('RECORDS_PER_PAGE', 20);

// Session Timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

/**
 * Helper function to get BECE grade from marks
 */
function getBeceGrade($marks) {
    foreach (BECE_GRADES as $grade) {
        if ($marks >= $grade['min'] && $marks <= $grade['max']) {
            return $grade;
        }
    }
    return ['grade' => 'F9', 'remark' => 'Fail', 'points' => 9];
}

/**
 * Calculate BECE aggregate (best 6 subjects)
 */
function calculateAggregate($grades) {
    $points = array_column($grades, 'points');
    sort($points);
    return array_sum(array_slice($points, 0, 6));
}

/**
 * Global helper to access system settings
 */
function setting($key, $default = null) {
    return System::getSetting($key, $default);
}
