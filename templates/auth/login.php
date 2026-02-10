<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SCHOOL_NAME; ?></title>
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
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        .login-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e0e0e0;
            border-right: none;
            background: #f8f9fa;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .alert {
            border-radius: 10px;
        }
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="bi bi-mortarboard-fill"></i> <?php echo SCHOOL_NAME; ?></h1>
                <p><?php echo SCHOOL_MOTTO; ?></p>
            </div>
            <div class="login-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo Security::clean($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                $flash = null;
                if (isset($_SESSION['flash'])) {
                    $flash = $_SESSION['flash'];
                    unset($_SESSION['flash']);
                }
                if ($flash):
                ?>
                    <div class="alert alert-<?php echo $flash['type']; ?>" role="alert">
                        <?php echo Security::clean($flash['message']); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>auth/login">
                    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Enter your username" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 text-end">
                        <a href="<?php echo BASE_URL; ?>auth/forgotPassword" class="forgot-link">
                            Forgot Password?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <div class="mt-4 text-center">
                    <small class="text-muted">
                        Default credentials: <strong>admin</strong> / <strong>admin123</strong>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-white">
                &copy; <?php echo date('Y'); ?> <?php echo SCHOOL_NAME; ?>. All rights reserved.
            </small>
        </div>
    </div>
</body>
</html>
