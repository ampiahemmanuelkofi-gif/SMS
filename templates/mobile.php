<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $pageTitle; ?> | SIS Mobile</title>
    <link rel="manifest" href="<?php echo BASE_URL; ?>manifest.json">
    <meta name="theme-color" content="#4e73df">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root { --primary-color: #4e73df; --secondary-color: #858796; }
        body { background-color: #f8f9fc; padding-bottom: 70px; font-family: 'Segoe UI', Roboto, sans-serif; }
        .mobile-header { background: var(--primary-color); color: white; padding: 15px; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .bottom-nav { background: white; border-top: 1px solid #e3e6f0; position: fixed; bottom: 0; width: 100%; display: flex; justify-content: space-around; padding: 10px 0; z-index: 1000; }
        .nav-item-mobile { text-align: center; color: var(--secondary-color); text-decoration: none; font-size: 12px; }
        .nav-item-mobile.active { color: var(--primary-color); }
        .nav-item-mobile i { font-size: 20px; display: block; }
        .card-mobile { border: none; border-radius: 12px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1); margin-bottom: 20px; }
        .avatar-mobile { width: 45px; height: 45px; border-radius: 50%; background: #eaecf4; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

    <div class="mobile-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><?php echo $pageTitle; ?></h5>
        <a href="<?php echo BASE_URL; ?>dashboard" class="text-white"><i class="bi bi-x-lg"></i></a>
    </div>

    <div class="container-fluid py-3">
        <?php 
            $flash = $this->getFlash();
            if ($flash): 
        ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show small mb-3">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
        <?php endif; ?>

        <?php echo $content; ?>
    </div>

    <nav class="bottom-nav">
        <?php if (isset($app_mode) && $app_mode === 'parent'): ?>
            <a href="<?php echo BASE_URL; ?>iparent" class="nav-item-mobile <?php echo ($_SERVER['REQUEST_URI'] == '/sch/iparent') ? 'active' : ''; ?>">
                <i class="bi bi-house-door"></i> Hub
            </a>
            <a href="<?php echo BASE_URL; ?>iparent/grades" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'grades')) ? 'active' : ''; ?>">
                <i class="bi bi-award"></i> Grades
            </a>
            <a href="<?php echo BASE_URL; ?>iparent/finances" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'finances')) ? 'active' : ''; ?>">
                <i class="bi bi-credit-card"></i> Fees
            </a>
            <a href="#" class="nav-item-mobile">
                <i class="bi bi-chat-dots"></i> Chat
            </a>
        <?php elseif (isset($app_mode) && $app_mode === 'student'): ?>
            <a href="<?php echo BASE_URL; ?>istudent" class="nav-item-mobile <?php echo ($_SERVER['REQUEST_URI'] == '/sch/istudent') ? 'active' : ''; ?>">
                <i class="bi bi-house-door"></i> Hub
            </a>
            <a href="<?php echo BASE_URL; ?>istudent/timetable" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'timetable')) ? 'active' : ''; ?>">
                <i class="bi bi-calendar3"></i> Schedule
            </a>
            <a href="<?php echo BASE_URL; ?>istudent/assignments" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'assignments')) ? 'active' : ''; ?>">
                <i class="bi bi-book"></i> Homework
            </a>
            <a href="#" class="nav-item-mobile">
                <i class="bi bi-qr-code-scan"></i> My ID
            </a>
        <?php else: // Default to Teacher App ?>
            <a href="<?php echo BASE_URL; ?>iteacher" class="nav-item-mobile <?php echo ($_SERVER['REQUEST_URI'] == '/sch/iteacher') ? 'active' : ''; ?>">
                <i class="bi bi-house-door"></i> Hub
            </a>
            <a href="<?php echo BASE_URL; ?>iteacher/attendance" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'attendance')) ? 'active' : ''; ?>">
                <i class="bi bi-check2-circle"></i> Attendance
            </a>
            <a href="<?php echo BASE_URL; ?>iteacher/timetable" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'timetable')) ? 'active' : ''; ?>">
                <i class="bi bi-calendar3"></i> Schedule
            </a>
            <a href="<?php echo BASE_URL; ?>iteacher/behavior" class="nav-item-mobile <?php echo (strpos($_SERVER['REQUEST_URI'], 'behavior')) ? 'active' : ''; ?>">
                <i class="bi bi-journal-text"></i> Behavior
            </a>
        <?php endif; ?>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?php echo BASE_URL; ?>service-worker.js');
        }
    </script>
</body>
</html>
