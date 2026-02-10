<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo SCHOOL_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .forgot-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .forgot-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .forgot-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        .forgot-body {
            padding: 40px 30px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
        }
        .back-link {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <h1><i class="bi bi-key-fill"></i> Forgot Password</h1>
            </div>
            <div class="forgot-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo Security::clean($error); ?></div>
                <?php endif; ?>
                
                <p class="text-muted mb-4">Enter your email address and we'll send you instructions to reset your password.</p>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter your email" required autofocus>
                    </div>
                    
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-send-fill"></i> Send Reset Link
                    </button>
                </form>
                
                <div class="mt-3 text-center">
                    <a href="<?php echo BASE_URL; ?>auth/login" class="back-link">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
