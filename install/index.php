<?php
/**
 * Installation/Setup Script (MVP)
 */

if (file_exists('../config/settings.php')) {
    die("System already installed. Please remove install directory for security.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];
    
    try {
        $conn = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database
        $conn->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->exec("USE `$db_name`");
        
        // Run schema
        $schema = file_get_contents('../database/schema.sql');
        $conn->exec($schema);
        
        // Run seed
        if (isset($_POST['seed_data'])) {
            $seed = file_get_contents('../database/seed.sql');
            $conn->exec($seed);
        }
        
        // Create config file
        $config = "<?php\n" . 
                  "// Database Configuration\n" .
                  "define('DB_HOST', '$db_host');\n" .
                  "define('DB_USER', '$db_user');\n" .
                  "define('DB_PASS', '$db_pass');\n" .
                  "define('DB_NAME', '$db_name');\n" .
                  "\n// Application Settings\n" .
                  "define('BASE_URL', '" . $_POST['base_url'] . "');\n";
                  
        file_put_contents('../config/settings.php', $config);
        
        echo "<h1>Installation Successful!</h1><p><a href='../auth/login'>Go to Login</a></p>";
        exit;
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Setup - School System</title></head>
<body>
    <h2>School System Installation</h2>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <p>DB Host: <input name="db_host" value="localhost" required></p>
        <p>DB User: <input name="db_user" value="root" required></p>
        <p>DB Pass: <input name="db_pass" value=""></p>
        <p>DB Name: <input name="db_name" value="school_management" required></p>
        <p>Base URL: <input name="base_url" value="http://localhost/sch/" required></p>
        <p><input type="checkbox" name="seed_data" checked> Load sample data (classes, subjects, admin user)</p>
        <button type="submit">Install System</button>
    </form>
</body>
</html>
