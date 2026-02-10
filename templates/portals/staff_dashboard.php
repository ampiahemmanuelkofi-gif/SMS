<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title text-dark"><i class="bi bi-briefcase-fill"></i> Staff Workspace</h2>
        <p class="text-muted">Manage your classes and personal records.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 h-100">
            <div class="bg-primary-subtle rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-journal-check text-primary fs-3"></i>
            </div>
            <h6 class="text-muted small text-uppercase">My Classes</h6>
            <h4 class="fw-bold mb-0"><?php echo count($classes); ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 h-100 text-decoration-none dropdown-toggle-split" onclick="location.href='<?php echo BASE_URL; ?>hr/payroll'">
            <div class="bg-success-subtle rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-cash-coin text-success fs-3"></i>
            </div>
            <h6 class="text-muted small text-uppercase">Last Salary</h6>
            <h4 class="fw-bold mb-0">GHS <?php echo $recent_payroll[0]['net']; ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 h-100">
            <div class="bg-info-subtle rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-chat-left-dots text-info fs-3"></i>
            </div>
            <h6 class="text-muted small text-uppercase">Staff Messages</h6>
            <h4 class="fw-bold mb-0">5</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4 h-100">
            <div class="bg-warning-subtle rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-clock-history text-warning fs-3"></i>
            </div>
            <h6 class="text-muted small text-uppercase">Leave Balance</h6>
            <h4 class="fw-bold mb-0">12 Days</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Class Shortcuts -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Recent Payroll History</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_payroll as $payroll): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo $payroll['month']; ?></h6>
                                <small class="badge bg-success-subtle text-success"><?php echo $payroll['status']; ?></small>
                            </div>
                            <div class="text-end">
                                <h6 class="fw-bold mb-1">GHS <?php echo $payroll['net']; ?></h6>
                                <a href="#" class="btn btn-sm btn-link p-0">Download Payslip</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Notices -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Internal Announcements</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($announcements as $a): ?>
                        <div class="list-group-item p-3">
                            <h6 class="fw-bold mb-1"><?php echo Security::clean($a['title']); ?></h6>
                            <p class="small text-muted mb-0"><?php echo substr(Security::clean($a['content']), 0, 80); ?>...</p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-3">
                    <a href="<?php echo BASE_URL; ?>communication" class="btn btn-primary btn-sm w-100">Notice Board</a>
                </div>
            </div>
        </div>
    </div>
</div>
