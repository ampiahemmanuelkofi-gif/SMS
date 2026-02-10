<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance - <?php echo SCHOOL_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .maintenance-card {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: #fff7ed;
            color: #f97316;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <div class="icon-circle">
            <i class="bi bi-tools"></i>
        </div>
        <h2 class="fw-bold mb-3">Under Maintenance</h2>
        <p class="text-muted mb-4">
            <?php echo SCHOOL_NAME; ?> system is currently undergoing scheduled maintenance to improve our services. We'll be back online shortly.
        </p>
        <div class="d-grid">
            <a href="<?php echo BASE_URL; ?>auth/login" class="btn btn-primary py-2 rounded-3">
                Admin Login
            </a>
        </div>
        <p class="mt-4 small text-muted">Thank you for your patience.</p>
    </div>
</body>
</html>
