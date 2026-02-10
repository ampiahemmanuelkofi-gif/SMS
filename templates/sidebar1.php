<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container shadow-sm">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="school-info">
            <h4><?php echo SCHOOL_NAME; ?></h4>
            <p><?php echo SCHOOL_MOTTO; ?></p>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <ul class="nav flex-column" id="sidebarNav">
            <?php
            $role = Auth::getRole();
            $currentUrl = $_SERVER['REQUEST_URI'];
            
            if (!function_exists('isActive')) {
                function isActive($url, $current) {
                    return strpos($current, $url) !== false ? 'active' : '';
                }
            }
            ?>
            
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>dashboard" class="nav-link <?php echo isActive('/dashboard', $currentUrl); ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <?php if (in_array($role, ['super_admin', 'admin'])): ?>
                <li class="nav-header">Administration</li>
                
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>students" class="nav-link <?php echo isActive('/students', $currentUrl); ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Student Registry</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>admissions" class="nav-link <?php echo isActive('/admissions', $currentUrl); ?>">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Admissions</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#hrSubmenu" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>HR Management</span>
                        <i class="bi bi-chevron-down ms-auto x-small"></i>
                    </a>
                    <div id="hrSubmenu" class="collapse">
                        <ul class="nav flex-column ps-3 submenu">
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>hr/directory">Staff Directory</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>hr/leave">Leaves</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>hr/payroll">Payroll</a></li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            
            <li class="nav-header">Academic Engine</li>
            
            <!-- Academics -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#academicSubmenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-book-fill"></i>
                    <span>Knowledge Hub</span>
                    <i class="bi bi-chevron-down ms-auto x-small"></i>
                </a>
                <div id="academicSubmenu" class="collapse">
                    <ul class="nav flex-column ps-3 submenu">
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>academics/classes">Classes & Sections</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>academics/subjects">Subject Bank</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>timetable">Master Timetable</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>lms">E-Learning (LMS)</a></li>
                    </ul>
                </div>
            </li>

            <!-- Results -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#assessmentsSubmenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    <span>Assessments</span>
                    <i class="bi bi-chevron-down ms-auto x-small"></i>
                </a>
                <div id="assessmentsSubmenu" class="collapse">
                    <ul class="nav flex-column ps-3 submenu">
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>assessments/entry">Marks Entry</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>exam">Exam Sessions</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>reports">Term Reports</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>attendance/mark" class="nav-link <?php echo isActive('/attendance', $currentUrl); ?>">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Attendance</span>
                </a>
            </li>

            <?php if (in_array($role, ['super_admin', 'admin'])): ?>
                <li class="nav-header">Operations</li>
                
                <!-- Resources -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#resourceSubmenu" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Campus Resources</span>
                        <i class="bi bi-chevron-down ms-auto x-small"></i>
                    </a>
                    <div id="resourceSubmenu" class="collapse">
                        <ul class="nav flex-column ps-3 submenu">
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>inventory">Inventory & Assets</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>library">Library Hub</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>transport">Transport Fleet</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>hostel">Boarding/Hostel</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>fees" class="nav-link <?php echo isActive('/fees', $currentUrl); ?>">
                        <i class="bi bi-wallet2"></i>
                        <span>Finance & Fees</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-header">Experience</li>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#mobileMenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-phone-fill"></i>
                    <span>Mobile Apps</span>
                    <i class="bi bi-chevron-down ms-auto x-small"></i>
                </a>
                <div id="mobileMenu" class="collapse">
                    <ul class="nav flex-column ps-3 submenu">
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>iteacher">iTeacher Mobile</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>iparent">iParent Hub</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>istudent">iStudent Portal</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>support" class="nav-link <?php echo isActive('/support', $currentUrl); ?>">
                    <i class="bi bi-headset"></i>
                    <span>Support Hub</span>
                </a>
            </li>

            <?php if (Auth::hasRole('super_admin')): ?>
                <li class="nav-header">Core Control</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>admin" class="nav-link <?php echo isActive('/admin', $currentUrl); ?>">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>System Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>ai" class="nav-link">
                        <i class="bi bi-cpu-fill"></i>
                        <span>AI Intelligence</span>
                        <span class="badge bg-danger ms-2" style="font-size: 8px;">PRO</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Sidebar Footer Profile -->
    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>profile" class="mini-profile shadow-sm">
            <div class="mini-avatar">
                <?php echo strtoupper(substr(Auth::getFullName(), 0, 1)); ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold small text-truncate"><?php echo Auth::getFullName(); ?></div>
                <div class="x-small opacity-50 text-truncate"><?php echo Auth::getRole(); ?></div>
            </div>
            <div class="ms-auto">
                <i class="bi bi-chevron-right opacity-25"></i>
            </div>
        </a>
    </div>
</div>

<style>
    .x-small { font-size: 10px; }
</style>
