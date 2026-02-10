<?php
/**
 * Fix Admin Password Script
 * Run this by visiting http://localhost/sch/fix_admin.php
 */

require_once 'config/database.php';
require_once 'core/Auth.php';

$db = getDbConnection();

$newPassword = 'admin123';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);

try {
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hash]);
    
    echo "<h1>Admin password fixed!</h1>";
    echo "<p>You can now log in with: <strong>admin</strong> / <strong>admin123</strong></p>";
    echo "<p><a href='auth/login'>Go to Login</a></p>";
    
    // Self-delete for security (optional, but recommended)
    // unlink(__FILE__);
    
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
