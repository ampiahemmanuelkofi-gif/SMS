<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container shadow-sm">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="school-info">
            <h4><?php echo setting('school_name', SCHOOL_NAME); ?></h4>
            <p><?php echo setting('school_motto', SCHOOL_MOTTO); ?></p>
        </div>
    </div>

    <div class="sidebar-menu">
        <ul class="nav flex-column" id="sidebarNav">
            <?php
            $role = Auth::getRole();
            $currentUrl = $_SERVER['REQUEST_URI'];

            function isActive($url, $current) {
                return strpos($current, $url) !== false ? 'active' : '';
            }

            function isParentActive($paths, $current) {
                foreach ($paths as $path) {
                    if (strpos($current, $path) !== false) return 'show';
                }
                return '';
            }
            ?>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>dashboard" class="nav-link <?= isActive('/dashboard', $currentUrl); ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if (Auth::canAccess('students') || Auth::canAccess('admissions') || Auth::canAccess('hr') || Auth::canAccess('safeguarding')): ?>
                <li class="nav-header">Administration</li>

                <?php if (Auth::canAccess('students')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>students" class="nav-link <?= isActive('/students', $currentUrl); ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Student Registry</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (System::isModuleEnabled('module_admissions')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>admissions" class="nav-link <?= isActive('/admissions', $currentUrl); ?>">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Admissions</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- HR -->
                <?php if (System::isModuleEnabled('module_hr') && Auth::canAccess('hr')): ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#hrSubmenu" role="button"
                       aria-expanded="<?= isParentActive(['/hr/'], $currentUrl) ? 'true' : 'false'; ?>">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>HR Management</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div id="hrSubmenu" class="collapse <?= isParentActive(['/hr/'], $currentUrl); ?>">
                        <ul class="nav flex-column ps-3 submenu">
                            <li class="nav-item"><a class="nav-link <?= isActive('/hr/directory', $currentUrl); ?>" href="<?= BASE_URL ?>hr/directory">Staff Directory</a></li>
                            <li class="nav-item"><a class="nav-link <?= isActive('/hr/leave', $currentUrl); ?>" href="<?= BASE_URL ?>hr/leave">Leaves</a></li>
                            <li class="nav-item"><a class="nav-link <?= isActive('/hr/payroll', $currentUrl); ?>" href="<?= BASE_URL ?>hr/payroll">Payroll</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <!-- Safeguarding -->
                <?php if (System::isModuleEnabled('module_safeguarding') && Auth::canAccess('safeguarding')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>safeguarding" class="nav-link <?= isActive('/safeguarding', $currentUrl); ?>">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Safeguarding Hub</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (Auth::canAccess('academics') || Auth::canAccess('assessments') || Auth::canAccess('attendance')): ?>
            <li class="nav-header">Academic Engine</li>

            <!-- Academics -->
            <?php if (Auth::canAccess('academics')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#academicSubmenu"
                   aria-expanded="<?= isParentActive(['/academics','/timetable','/lms'], $currentUrl) ? 'true' : 'false'; ?>">
                    <i class="bi bi-book-fill"></i>
                    <span>Knowledge Hub</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div id="academicSubmenu" class="collapse <?= isParentActive(['/academics','/timetable','/lms'], $currentUrl); ?>">
                    <ul class="nav flex-column ps-3 submenu">
                        <li><a class="nav-link <?= isActive('/academics/classes', $currentUrl); ?>" href="<?= BASE_URL ?>academics/classes">Classes & Sections</a></li>
                        <li><a class="nav-link <?= isActive('/academics/subjects', $currentUrl); ?>" href="<?= BASE_URL ?>academics/subjects">Subject Bank</a></li>
                        <li><a class="nav-link <?= isActive('/timetable', $currentUrl); ?>" href="<?= BASE_URL ?>timetable">Master Timetable</a></li>
                        <?php if (System::isModuleEnabled('module_lms')): ?>
                        <li><a class="nav-link <?= isActive('/lms', $currentUrl); ?>" href="<?= BASE_URL ?>lms">E-Learning (LMS)</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- Assessments -->
            <?php if (Auth::canAccess('assessments')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#assessmentsSubmenu"
                   aria-expanded="<?= isParentActive(['/assessments','/exam','/reports'], $currentUrl) ? 'true' : 'false'; ?>">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    <span>Assessments</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div id="assessmentsSubmenu" class="collapse <?= isParentActive(['/assessments','/exam','/reports'], $currentUrl); ?>">
                    <ul class="nav flex-column ps-3 submenu">
                        <?php if (Auth::hasRole(['super_admin', 'admin', 'teacher'])): // Keep specific role checks for inner actions if too granular ?>
                        <li><a class="nav-link <?= isActive('/assessments/entry', $currentUrl); ?>" href="<?= BASE_URL ?>assessments/entry">Marks Entry</a></li>
                        <?php endif; ?>
                        <li><a class="nav-link <?= isActive('/exam', $currentUrl); ?>" href="<?= BASE_URL ?>exam"><?= in_array($role, ['student', 'parent']) ? 'Results & Exams' : 'Exam Sessions'; ?></a></li>
                        <li><a class="nav-link <?= isActive('/reports', $currentUrl); ?>" href="<?= BASE_URL ?>reports">Term Reports</a></li>
                    </ul>
                </div>
            </li>

            <?php if (Auth::canAccess('attendance')): ?>
            <li class="nav-item">
                <a href="<?= BASE_URL ?>attendance/mark" class="nav-link <?= isActive('/attendance', $currentUrl); ?>">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (Auth::canAccess('finance')): ?>
            <?php if (System::isModuleEnabled('module_finance')): ?>
            <!-- Finance & Fees -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#financeSubmenu"
                   aria-expanded="<?= isParentActive(['/fees'], $currentUrl) ? 'true' : 'false'; ?>">
                    <i class="bi bi-wallet2"></i>
                    <span>Finance & Fees</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div id="financeSubmenu" class="collapse <?= isParentActive(['/fees'], $currentUrl); ?>">
                    <ul class="nav flex-column ps-3 submenu">
                        <?php if (Auth::hasRole(['super_admin', 'admin', 'accountant'])): ?>
                        <li><a class="nav-link <?= isActive('/fees', $currentUrl); ?>" href="<?= BASE_URL ?>fees">Dashboard</a></li>
                        <li><a class="nav-link <?= isActive('/fees/structure', $currentUrl); ?>" href="<?= BASE_URL ?>fees/structure">Fee Structure</a></li>
                        <li><a class="nav-link <?= isActive('/fees/invoices', $currentUrl); ?>" href="<?= BASE_URL ?>fees/invoices">Invoices</a></li>
                        <li><a class="nav-link <?= isActive('/fees/collect', $currentUrl); ?>" href="<?= BASE_URL ?>fees/collect">Collect Payment</a></li>
                        <li><a class="nav-link <?= isActive('/fees/defaulters', $currentUrl); ?>" href="<?= BASE_URL ?>fees/defaulters">Defaulters</a></li>
                        <?php else: ?>
                        <li><a class="nav-link <?= isActive('/fees/my-invoices', $currentUrl); ?>" href="<?= BASE_URL ?>fees/my-invoices">My Invoices</a></li>
                        <li><a class="nav-link <?= isActive('/fees/history', $currentUrl); ?>" href="<?= BASE_URL ?>fees/history">Payment History</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (Auth::canAccess('inventory') || Auth::canAccess('library') || Auth::canAccess('transport') || Auth::canAccess('hostel') || Auth::canAccess('health') || Auth::canAccess('cafeteria')): ?>
                <?php 
                $inventory = System::isModuleEnabled('module_inventory');
                $library = System::isModuleEnabled('module_library');
                $transport = System::isModuleEnabled('module_transport');
                $hostel = System::isModuleEnabled('module_hostel');
                $health = System::isModuleEnabled('module_health');
                $cafeteria = System::isModuleEnabled('module_cafeteria');
                
                if ($inventory || $library || $transport || $hostel || $health || $cafeteria): ?>
                <li class="nav-header">Operations</li>

                <!-- Resources -->
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#resourceSubmenu"
                       aria-expanded="<?= isParentActive(['/inventory','/library','/transport','/hostel','/health','/cafeteria'], $currentUrl) ? 'true' : 'false'; ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Campus Resources</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div id="resourceSubmenu" class="collapse <?= isParentActive(['/inventory','/library','/transport','/hostel','/health','/cafeteria'], $currentUrl); ?>">
                        <ul class="nav flex-column ps-3 submenu">
                            <?php if ($inventory && Auth::canAccess('inventory')): ?>
                                <li><a class="nav-link <?= isActive('/inventory', $currentUrl); ?>" href="<?= BASE_URL ?>inventory">Inventory & Assets</a></li>
                            <?php endif; ?>
                            
                            <?php if ($library && Auth::canAccess('library')): ?>
                                <li><a class="nav-link <?= isActive('/library', $currentUrl); ?>" href="<?= BASE_URL ?>library">Library Hub</a></li>
                            <?php endif; ?>
                            
                            <?php if ($transport && Auth::canAccess('transport')): ?>
                                <li><a class="nav-link <?= isActive('/transport', $currentUrl); ?>" href="<?= BASE_URL ?>transport">Transport Fleet</a></li>
                            <?php endif; ?>
                            
                            <?php if ($hostel && Auth::canAccess('hostel')): ?>
                                <li><a class="nav-link <?= isActive('/hostel', $currentUrl); ?>" href="<?= BASE_URL ?>hostel">Boarding/Hostel</a></li>
                            <?php endif; ?>

                            <?php if ($health && Auth::canAccess('health')): ?>
                                <li><a class="nav-link <?= isActive('/health', $currentUrl); ?>" href="<?= BASE_URL ?>health">Health & Medical</a></li>
                            <?php endif; ?>

                            <?php if ($cafeteria && Auth::canAccess('cafeteria')): ?>
                                <li><a class="nav-link <?= isActive('/cafeteria', $currentUrl); ?>" href="<?= BASE_URL ?>cafeteria">Cafeteria Hub</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
            <?php endif; ?>

            <li class="nav-header">Experience</li>

            <?php if (Auth::canAccess('support')): ?>
            <li class="nav-item">
                <a href="<?= BASE_URL ?>support" class="nav-link <?= isActive('/support', $currentUrl); ?>">
                    <i class="bi bi-headset"></i>
                    <span><?= in_array($role, ['receptionist', 'admin', 'super_admin']) ? 'Support & Helpdesk' : 'Help & Support'; ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (Auth::canAccess('alumni')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>alumni" class="nav-link <?= isActive('/alumni', $currentUrl); ?>">
                        <i class="bi bi-mortarboard"></i>
                        <span>Alumni Portal</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (Auth::hasRole('super_admin')): ?>
                <li class="nav-header">Core Control</li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>admin" class="nav-link <?= isActive('/admin', $currentUrl); ?>">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>System Admin</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>profile" class="mini-profile shadow-sm">
            <div class="mini-avatar">
                <?= strtoupper(substr(Auth::getFullName(), 0, 1)); ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold small text-truncate"><?= Auth::getFullName(); ?></div>
                <div class="x-small opacity-50 text-truncate"><?= Auth::getRole(); ?></div>
            </div>
            <div class="ms-auto">
                <i class="bi bi-chevron-right opacity-25"></i>
            </div>
        </a>
    </div>
</div>
