<?php
/**
 * System Settings - Enterprise Admin Panel
 * Comprehensive settings page with vertical tabs
 */

$activeTab = $activeTab ?? 'school';
$s = $settings ?? [];

// Helper function to get setting value (local scope for this template)
function getSetting($key, $default = '') {
    global $s;
    return isset($s[$key]) ? htmlspecialchars($s[$key]) : $default;
}
?>

<div class="mb-4">
    <h1 class="page-title"><i class="bi bi-gear-fill me-2"></i>System Settings</h1>
    <p class="text-muted mb-0">Manage your school configuration and system preferences</p>
</div>

<div class="row g-4">
    <!-- Vertical Tabs Navigation -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-2">
                <nav class="nav flex-column nav-pills" role="tablist">
                    <a class="nav-link <?php echo $activeTab === 'school' ? 'active' : ''; ?>" href="?tab=school">
                        <i class="bi bi-building me-2"></i> School Information
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'academic' ? 'active' : ''; ?>" href="?tab=academic">
                        <i class="bi bi-book me-2"></i> Academic Settings
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'users' ? 'active' : ''; ?>" href="?tab=users">
                        <i class="bi bi-people me-2"></i> Users & Roles
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'finance' ? 'active' : ''; ?>" href="?tab=finance">
                        <i class="bi bi-wallet2 me-2"></i> Finance & Fees
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'notifications' ? 'active' : ''; ?>" href="?tab=notifications">
                        <i class="bi bi-bell me-2"></i> Notifications
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'appearance' ? 'active' : ''; ?>" href="?tab=appearance">
                        <i class="bi bi-palette me-2"></i> Appearance
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'security' ? 'active' : ''; ?>" href="?tab=security">
                        <i class="bi bi-shield-lock me-2"></i> Security
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'modules' ? 'active' : ''; ?>" href="?tab=modules">
                        <i class="bi bi-grid-3x3 me-2"></i> Module Control
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'documents' ? 'active' : ''; ?>" href="?tab=documents">
                        <i class="bi bi-file-earmark me-2"></i> Documents & ID
                    </a>
                    <a class="nav-link <?php echo $activeTab === 'backup' ? 'active' : ''; ?>" href="?tab=backup">
                        <i class="bi bi-database me-2"></i> Backup & Maintenance
                    </a>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Tab Content -->
    <div class="col-lg-9">
        <?php if ($activeTab === 'school'): ?>
        <!-- School Information -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>School Information</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/school" enctype="multipart/form-data">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">School Name</label>
                            <input type="text" class="form-control" name="settings[school_name]" 
                                   value="<?php echo getSetting('school_name'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motto</label>
                            <input type="text" class="form-control" name="settings[school_motto]" 
                                   value="<?php echo getSetting('school_motto'); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="settings[school_address]" rows="2"><?php echo getSetting('school_address'); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="settings[school_phone]" 
                                   value="<?php echo getSetting('school_phone'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="settings[school_email]" 
                                   value="<?php echo getSetting('school_email'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" name="settings[school_website]" 
                                   value="<?php echo getSetting('school_website'); ?>" placeholder="https://">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">School Type</label>
                            <select class="form-select" name="settings[school_type]">
                                <option value="primary" <?php echo getSetting('school_type') === 'primary' ? 'selected' : ''; ?>>Primary School</option>
                                <option value="jhs" <?php echo getSetting('school_type') === 'jhs' ? 'selected' : ''; ?>>Junior High School</option>
                                <option value="shs" <?php echo getSetting('school_type') === 'shs' ? 'selected' : ''; ?>>Senior High School</option>
                                <option value="international" <?php echo getSetting('school_type') === 'international' ? 'selected' : ''; ?>>International School</option>
                                <option value="combined" <?php echo getSetting('school_type') === 'combined' ? 'selected' : ''; ?>>Combined (Multi-level)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic System</label>
                            <select class="form-select" name="settings[academic_system]">
                                <option value="term" <?php echo getSetting('academic_system') === 'term' ? 'selected' : ''; ?>>Term-Based (3 Terms)</option>
                                <option value="semester" <?php echo getSetting('academic_system') === 'semester' ? 'selected' : ''; ?>>Semester-Based (2 Semesters)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Logo Upload -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2 text-primary"></i>School Logo</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/uploadLogo" enctype="multipart/form-data">
                    <?php echo Security::csrfInput(); ?>
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <?php if (!empty($s['school_logo'])): ?>
                                <img src="<?php echo BASE_URL . getSetting('school_logo'); ?>" alt="Logo" class="rounded" style="max-height: 80px;">
                            <?php else: ?>
                                <div class="avatar avatar-xl bg-soft-primary text-primary">
                                    <i class="bi bi-building"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <div class="form-text">Recommended: 200x200px, PNG or JPG</div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'academic'): ?>
        <!-- Academic Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-book me-2 text-primary"></i>Academic Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/academic">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Current Academic Year</label>
                            <input type="text" class="form-control" name="settings[current_academic_year]" 
                                   value="<?php echo getSetting('current_academic_year', date('Y') . '/' . (date('Y')+1)); ?>" placeholder="2024/2025">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Term/Semester</label>
                            <select class="form-select" name="settings[current_term]">
                                <option value="1" <?php echo getSetting('current_term') === '1' ? 'selected' : ''; ?>>Term 1 / Semester 1</option>
                                <option value="2" <?php echo getSetting('current_term') === '2' ? 'selected' : ''; ?>>Term 2 / Semester 2</option>
                                <option value="3" <?php echo getSetting('current_term') === '3' ? 'selected' : ''; ?>>Term 3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Grading System</label>
                            <select class="form-select" name="settings[grading_system]">
                                <option value="percentage" <?php echo getSetting('grading_system') === 'percentage' ? 'selected' : ''; ?>>Percentage (%)</option>
                                <option value="gpa" <?php echo getSetting('grading_system') === 'gpa' ? 'selected' : ''; ?>>GPA (4.0 Scale)</option>
                                <option value="letter" <?php echo getSetting('grading_system') === 'letter' ? 'selected' : ''; ?>>Letter Grades (A-F)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pass Mark (%)</label>
                            <input type="number" class="form-control" name="settings[pass_mark]" 
                                   value="<?php echo getSetting('pass_mark', '50'); ?>" min="0" max="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Class Ranking</label>
                            <select class="form-select" name="settings[class_ranking]">
                                <option value="1" <?php echo getSetting('class_ranking') === '1' ? 'selected' : ''; ?>>Enabled</option>
                                <option value="0" <?php echo getSetting('class_ranking') === '0' ? 'selected' : ''; ?>>Disabled</option>
                            </select>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Continuous Assessment Weight (%)</label>
                            <input type="number" class="form-control" name="settings[ca_weight]" 
                                   value="<?php echo getSetting('ca_weight', '30'); ?>" min="0" max="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Exam Weight (%)</label>
                            <input type="number" class="form-control" name="settings[exam_weight]" 
                                   value="<?php echo getSetting('exam_weight', '70'); ?>" min="0" max="100">
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'users'): ?>
        <!-- Users & Roles -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>User & Role Management</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/users">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Minimum Password Length</label>
                            <input type="number" class="form-control" name="settings[min_password_length]" 
                                   value="<?php echo getSetting('min_password_length', '8'); ?>" min="6" max="32">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-control" name="settings[session_timeout]" 
                                   value="<?php echo getSetting('session_timeout', '30'); ?>" min="5" max="480">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Complexity</label>
                            <select class="form-select" name="settings[password_complexity]">
                                <option value="low" <?php echo getSetting('password_complexity') === 'low' ? 'selected' : ''; ?>>Low (letters only)</option>
                                <option value="medium" <?php echo getSetting('password_complexity') === 'medium' ? 'selected' : ''; ?>>Medium (letters + numbers)</option>
                                <option value="high" <?php echo getSetting('password_complexity') === 'high' ? 'selected' : ''; ?>>High (letters + numbers + symbols)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Allow Self Password Reset</label>
                            <select class="form-select" name="settings[allow_password_reset]">
                                <option value="1" <?php echo getSetting('allow_password_reset') === '1' ? 'selected' : ''; ?>>Yes</option>
                                <option value="0" <?php echo getSetting('allow_password_reset') === '0' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-secondary">
                            <i class="bi bi-people me-1"></i> Manage Users
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'finance'): ?>
        <!-- Finance Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-primary"></i>Finance & Fee Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/finance">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <select class="form-select" name="settings[currency]">
                                <option value="GHS" <?php echo getSetting('currency') === 'GHS' ? 'selected' : ''; ?>>GHS (Ghana Cedi)</option>
                                <option value="USD" <?php echo getSetting('currency') === 'USD' ? 'selected' : ''; ?>>USD (US Dollar)</option>
                                <option value="GBP" <?php echo getSetting('currency') === 'GBP' ? 'selected' : ''; ?>>GBP (British Pound)</option>
                                <option value="EUR" <?php echo getSetting('currency') === 'EUR' ? 'selected' : ''; ?>>EUR (Euro)</option>
                                <option value="NGN" <?php echo getSetting('currency') === 'NGN' ? 'selected' : ''; ?>>NGN (Nigerian Naira)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" class="form-control" name="settings[currency_symbol]" 
                                   value="<?php echo getSetting('currency_symbol', 'GH₵'); ?>" maxlength="5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Allow Part Payments</label>
                            <select class="form-select" name="settings[allow_part_payment]">
                                <option value="1" <?php echo getSetting('allow_part_payment') === '1' ? 'selected' : ''; ?>>Yes</option>
                                <option value="0" <?php echo getSetting('allow_part_payment') === '0' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice Prefix</label>
                            <input type="text" class="form-control" name="settings[invoice_prefix]" 
                                   value="<?php echo getSetting('invoice_prefix', 'INV-'); ?>" placeholder="INV-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Receipt Prefix</label>
                            <input type="text" class="form-control" name="settings[receipt_prefix]" 
                                   value="<?php echo getSetting('receipt_prefix', 'RCP-'); ?>" placeholder="RCP-">
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <h6 class="mt-2">Payment Methods</h6>
                        
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[payment_cash]" value="1"
                                       <?php echo getSetting('payment_cash', '1') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Cash</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[payment_bank]" value="1"
                                       <?php echo getSetting('payment_bank', '1') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Bank Transfer</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[payment_momo]" value="1"
                                       <?php echo getSetting('payment_momo') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Mobile Money</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[payment_card]" value="1"
                                       <?php echo getSetting('payment_card') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Card Payment</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'notifications'): ?>
        <!-- Notification Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2 text-primary"></i>Notification Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/notifications">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="settings[enable_email]" value="1"
                                       <?php echo getSetting('enable_email') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium">Enable Email Notifications</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="settings[enable_sms]" value="1"
                                       <?php echo getSetting('enable_sms') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium">Enable SMS Notifications</label>
                            </div>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <h6>SMS Gateway Settings</h6>
                        
                        <div class="col-md-6">
                            <label class="form-label">SMS API Provider</label>
                            <select class="form-select" name="settings[sms_provider]">
                                <option value="hubtel" <?php echo getSetting('sms_provider') === 'hubtel' ? 'selected' : ''; ?>>Hubtel</option>
                                <option value="arkesel" <?php echo getSetting('sms_provider') === 'arkesel' ? 'selected' : ''; ?>>Arkesel</option>
                                <option value="twilio" <?php echo getSetting('sms_provider') === 'twilio' ? 'selected' : ''; ?>>Twilio</option>
                                <option value="termii" <?php echo getSetting('sms_provider') === 'termii' ? 'selected' : ''; ?>>Termii</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sender ID</label>
                            <input type="text" class="form-control" name="settings[sms_sender_id]" 
                                   value="<?php echo getSetting('sms_sender_id'); ?>" placeholder="SchoolName" maxlength="11">
                        </div>
                        <div class="col-12">
                            <label class="form-label">SMS API Key</label>
                            <input type="password" class="form-control" name="settings[sms_api_key]" 
                                   value="<?php echo getSetting('sms_api_key'); ?>" placeholder="Enter API key">
                        </div>
                        
                        <div class="col-12"><hr class="my-2"></div>
                        <h6>Auto Alerts</h6>
                        
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[alert_absence]" value="1"
                                       <?php echo getSetting('alert_absence') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Absence Notifications</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[alert_fee_due]" value="1"
                                       <?php echo getSetting('alert_fee_due') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Fee Due Reminders</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[alert_grades]" value="1"
                                       <?php echo getSetting('alert_grades') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">New Grades Posted</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'appearance'): ?>
        <!-- Appearance Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-palette me-2 text-primary"></i>Appearance & Branding</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/appearance">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Primary Color</label>
                            <input type="color" class="form-control form-control-color w-100" name="settings[primary_color]" 
                                   value="<?php echo getSetting('primary_color', '#2563EB'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Secondary Color</label>
                            <input type="color" class="form-control form-control-color w-100" name="settings[secondary_color]" 
                                   value="<?php echo getSetting('secondary_color', '#1E293B'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Accent Color</label>
                            <input type="color" class="form-control form-control-color w-100" name="settings[accent_color]" 
                                   value="<?php echo getSetting('accent_color', '#16A34A'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">System Theme</label>
                            <select class="form-select" name="settings[theme]">
                                <option value="light" <?php echo getSetting('theme') === 'light' ? 'selected' : ''; ?>>Light Mode</option>
                                <option value="dark" <?php echo getSetting('theme') === 'dark' ? 'selected' : ''; ?>>Dark Mode</option>
                                <option value="auto" <?php echo getSetting('theme') === 'auto' ? 'selected' : ''; ?>>Auto (System)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dashboard Layout</label>
                            <select class="form-select" name="settings[dashboard_layout]">
                                <option value="default" <?php echo getSetting('dashboard_layout') === 'default' ? 'selected' : ''; ?>>Default (Cards)</option>
                                <option value="compact" <?php echo getSetting('dashboard_layout') === 'compact' ? 'selected' : ''; ?>>Compact</option>
                                <option value="detailed" <?php echo getSetting('dashboard_layout') === 'detailed' ? 'selected' : ''; ?>>Detailed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'security'): ?>
        <!-- Security Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Security Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/security">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="settings[two_factor_auth]" value="1"
                                       <?php echo getSetting('two_factor_auth') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium">Two-Factor Authentication</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="settings[captcha_login]" value="1"
                                       <?php echo getSetting('captcha_login') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium">CAPTCHA on Login</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Login Attempt Limit</label>
                            <input type="number" class="form-control" name="settings[login_attempt_limit]" 
                                   value="<?php echo getSetting('login_attempt_limit', '5'); ?>" min="3" max="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lockout Duration (minutes)</label>
                            <input type="number" class="form-control" name="settings[lockout_duration]" 
                                   value="<?php echo getSetting('lockout_duration', '15'); ?>" min="5" max="60">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="settings[force_password_change]" value="1"
                                       <?php echo getSetting('force_password_change') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Force Password Change on First Login</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Audit Log Retention (days)</label>
                            <input type="number" class="form-control" name="settings[audit_retention]" 
                                   value="<?php echo getSetting('audit_retention', '90'); ?>" min="30" max="365">
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'modules'): ?>
        <!-- Module Control -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3 me-2 text-primary"></i>Module Control</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Enable or disable system modules. Disabled modules will be hidden from all users.</p>
                
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/modules">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <?php
                        $modules = [
                            ['key' => 'module_library', 'name' => 'Library Management', 'icon' => 'bi-book'],
                            ['key' => 'module_hostel', 'name' => 'Hostel/Boarding', 'icon' => 'bi-building'],
                            ['key' => 'module_transport', 'name' => 'Transport Fleet', 'icon' => 'bi-bus-front'],
                            ['key' => 'module_hr', 'name' => 'HR & Payroll', 'icon' => 'bi-people'],
                            ['key' => 'module_admissions', 'name' => 'Online Admissions', 'icon' => 'bi-person-plus'],
                            ['key' => 'module_parent_portal', 'name' => 'Parent Portal', 'icon' => 'bi-person-badge'],
                            ['key' => 'module_student_portal', 'name' => 'Student Portal', 'icon' => 'bi-mortarboard'],
                            ['key' => 'module_lms', 'name' => 'E-Learning (LMS)', 'icon' => 'bi-laptop'],
                            ['key' => 'module_inventory', 'name' => 'Inventory', 'icon' => 'bi-box-seam'],
                            ['key' => 'module_health', 'name' => 'Health Records', 'icon' => 'bi-heart-pulse'],
                        ];
                        foreach ($modules as $mod): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi <?php echo $mod['icon']; ?> text-primary"></i>
                                    <span class="fw-medium"><?php echo $mod['name']; ?></span>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="settings[<?php echo $mod['key']; ?>]" value="1"
                                           <?php echo getSetting($mod['key'], '1') === '1' ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'documents'): ?>
        <!-- Documents & ID Settings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark me-2 text-primary"></i>Document & ID Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/save/documents" enctype="multipart/form-data">
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Report Card Title</label>
                            <input type="text" class="form-control" name="settings[report_title]" 
                                   value="<?php echo getSetting('report_title', 'TERMINAL REPORT CARD'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student ID Prefix</label>
                            <input type="text" class="form-control" name="settings[student_id_prefix]" 
                                   value="<?php echo getSetting('student_id_prefix', 'STU-'); ?>" placeholder="STU-">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Report Card Footer Text</label>
                            <textarea class="form-control" name="settings[report_footer]" rows="2"><?php echo getSetting('report_footer'); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'backup'): ?>
        <!-- Backup & Maintenance -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="bi bi-database me-2 text-primary"></i>Backup & Maintenance</h6>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded text-center">
                            <i class="bi bi-download display-4 text-primary mb-3"></i>
                            <h6>Manual Backup</h6>
                            <p class="text-muted small">Download a complete backup of your database</p>
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/backup" class="d-inline">
                                <?php echo Security::csrfInput(); ?>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-database-down me-1"></i> Create Backup
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded text-center">
                            <i class="bi bi-tools display-4 text-warning mb-3"></i>
                            <h6>Maintenance Mode</h6>
                            <p class="text-muted small">Temporarily disable access for all users except admins</p>
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/maintenance" class="d-inline">
                                <?php echo Security::csrfInput(); ?>
                                <?php if (getSetting('maintenance_mode') === '1'): ?>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Disable Maintenance
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Enable Maintenance
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3">System Information</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr><td class="text-muted">PHP Version</td><td class="fw-medium"><?php echo PHP_VERSION; ?></td></tr>
                        <tr><td class="text-muted">Server Software</td><td class="fw-medium"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td></tr>
                        <tr><td class="text-muted">System Version</td><td class="fw-medium"><?php echo getSetting('system_version', '1.0.0'); ?></td></tr>
                        <tr><td class="text-muted">Last Backup</td><td class="fw-medium"><?php echo getSetting('last_backup', 'Never'); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        color: var(--text-secondary);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 4px;
        transition: var(--transition);
    }
    .nav-pills .nav-link:hover {
        background: var(--bg-hover);
        color: var(--primary);
    }
    .nav-pills .nav-link.active {
        background: var(--primary);
        color: white;
    }
    .form-control-color {
        height: 42px;
        padding: 4px;
    }
</style>
