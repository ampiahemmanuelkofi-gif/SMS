<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo setting('school_name', SCHOOL_NAME); ?></title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Enterprise Design System -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/variables.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/tables.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/forms.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/responsive.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --primary-soft: rgba(67, 97, 238, 0.1);
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #ef233c;
            
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
            
            --body-bg: #f8fafc;
            --topbar-height: 80px;
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --glass: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: #1e293b;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .page-title, .school-info h4 {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Modern Sidebar (Floating) */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1050;
            transition: var(--transition);
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .logo-container {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 8px 16px -4px rgba(67, 97, 238, 0.5);
        }
        
        .school-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: white;
            margin: 0;
            line-height: 1.2;
        }
        
        .school-info p {
            font-size: 10px;
            margin: 2px 0 0;
            opacity: 0.5;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .sidebar-menu {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-menu::-webkit-scrollbar { display: none; }
        
        .nav-header {
            padding: 12px 16px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            color: #475569;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--sidebar-text);
            font-weight: 500;
            font-size: 14px;
            border-radius: var(--radius-md);
            margin-bottom: 4px;
            transition: var(--transition);
        }
        
        .nav-link i {
            font-size: 18px;
            margin-right: 12px;
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }
        
        .nav-link:hover {
            color: white;
            background: var(--sidebar-hover-bg);
        }
        
        .nav-link.active {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 12px -2px rgba(67, 97, 238, 0.4);
        }
        
        .nav-link.active i { color: white; }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.2);
        }

        .mini-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            transition: var(--transition);
        }

        .mini-profile:hover { opacity: 0.8; }

        .mini-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        /* Topbar & Layout */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }
        
        .top-navbar {
            height: var(--topbar-height);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-xs);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .search-bar {
            background: #f1f5f9;
            border-radius: var(--radius-md);
            padding: 4px 10px;
            display: flex;
            align-items: center;
            width: 240px;
            max-width: 100%;
        }

        .search-bar input {
            background: transparent;
            border: none;
            outline: none;
            margin-left: 8px;
            font-size: 14px;
            width: 100%;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.03em;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .action-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: var(--transition);
            position: relative;
        }

        .action-icon:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .content-area {
            flex: 1;
            padding-bottom: 32px;
        }
        
        /* Modern Cards Override */
        .card {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow) !important;
            transition: var(--transition) !important;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg) !important;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 1199px) {
            .sidebar {
                transform: translateX(-110%);
                left: 0;
                top: 0;
                bottom: 0;
                border-radius: 0;
            }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .top-navbar { padding: 0 16px; }
            .search-bar { display: none; }
        }
        
        .content-area {
            padding: 24px;
            flex: 1;
        }
    </style>
</head>
<body>
    <?php require TEMPLATES_PATH . '/sidebar.php'; ?>
    
    <div class="main-content">

    <!-- Top Navbar -->
    <div class="top-navbar">

        <!-- Left Section -->
        <div class="d-flex align-items-center gap-3">

            <!-- Mobile Menu Button -->
            <button class="btn btn-sm d-xl-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>

            <!-- Page Title -->
            <h1 class="page-title mb-0">
                <?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?>
            </h1>

            <!-- Search -->
            <div class="search-bar ms-3 d-none d-lg-flex align-items-center">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" placeholder="Search students, staff, records">
            </div>

            <!-- Demo Mode Badge -->
            <?php if (defined('APP_DEMO_MODE') && APP_DEMO_MODE): ?>
                <div class="ms-3">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 border border-dark border-opacity-10 shadow-sm animate-pulse">
                        <i class="bi bi-shield-lock-fill me-1"></i> DEMO MODE
                    </span>
                </div>
                <style>
                    @keyframes pulse-demo {
                        0% { opacity: 1; }
                        50% { opacity: 0.7; }
                        100% { opacity: 1; }
                    }
                    .animate-pulse { animation: pulse-demo 2s infinite; }
                </style>
            <?php endif; ?>

        </div>

        <!-- Right Section -->
        <div class="d-flex align-items-center gap-2">

            <!-- Messages -->
            <a href="<?php echo BASE_URL; ?>communication/inbox" class="action-icon text-decoration-none">
                <i class="bi bi-chat-dots"></i>
            </a>

            <!-- Notifications -->
            <a href="<?php echo BASE_URL; ?>notices" class="action-icon text-decoration-none position-relative">
                <i class="bi bi-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">•</span>
            </a>

            <div class="vr mx-2"></div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 user-trigger" role="button" data-bs-toggle="dropdown">
                    
                    <div class="text-end d-none d-md-block">
                        <div class="fw-semibold small lh-1"><?php echo Auth::getFullName(); ?></div>
                        <small class="text-muted"><?php echo ucfirst(Auth::getRole()); ?></small>
                    </div>

                    <div class="user-avatar">
                        <?php echo strtoupper(substr(Auth::getFullName(), 0, 1)); ?>
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-2">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>

        </div>
    </div>

        
        <!-- Content Area -->
        <div class="content-area">
            <?php
            // Flash messages
            if (isset($_SESSION['flash'])) {
                $flash = $_SESSION['flash'];
                unset($_SESSION['flash']);
                $alertType = $flash['type'] === 'error' ? 'danger' : $flash['type'];
                echo '<div class="alert alert-' . $alertType . ' border-0 shadow-sm alert-dismissible fade show rounded-4 p-3 mb-4" role="alert">';
                echo '<i class="bi bi-info-circle-fill me-2"></i>' . Security::clean($flash['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
            }
            ?>
            
            <?php echo $content; ?>
        </div>

        <?php require TEMPLATES_PATH . '/footer-partial.php'; ?>
    </div>
    
    <!-- Partials -->
    <?php require TEMPLATES_PATH . '/partials/toast.php'; ?>
    <?php require TEMPLATES_PATH . '/partials/delete-modal.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Enterprise App JS -->
    <script>const BASE_URL = '<?php echo BASE_URL; ?>';</script>
    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
</body>
</html>

