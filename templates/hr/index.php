<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1"><i class="bi bi-person-workspace me-2 text-primary"></i>Human Resources</h1>
        <p class="text-muted mb-0">Manage staff records, recruitment, and payroll operations</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>hr/add_employee" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Add Employee
        </a>
    </div>
</div>

<!-- HR Stats Dashboard -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-primary text-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-value"><?php echo $stats['employees']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Total Staff</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-warning text-warning">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-value"><?php echo $stats['pending_leaves']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Pending Leaves</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-success text-success">
                <i class="bi bi-person-plus"></i>
            </div>
            <div class="stat-value"><?php echo $stats['applicants']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">New Applicants</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-info text-info">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-value"><?php echo $stats['employees']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Active Contracts</div>
        </div>
    </div>
</div>

<!-- HR Action Hub -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <div class="avatar avatar-xl bg-soft-primary text-primary mx-auto mb-4">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">Staff Directory</h5>
                <p class="text-muted small mb-4">View and manage employee profiles and designations.</p>
                <a href="<?php echo BASE_URL; ?>hr/directory" class="btn btn-soft-primary w-100 py-2">
                    Open Directory <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <div class="avatar avatar-xl bg-soft-warning text-warning mx-auto mb-4">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h5 class="fw-bold mb-2">Leave Management</h5>
                <p class="text-muted small mb-4">Approve leave requests and track staff absences.</p>
                <a href="<?php echo BASE_URL; ?>hr/leave" class="btn btn-soft-warning w-100 py-2">
                    Manage Absences <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
                <div class="avatar avatar-xl bg-soft-success text-success mx-auto mb-4">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h5 class="fw-bold mb-2">Payroll Central</h5>
                <p class="text-muted small mb-4">Process monthly salaries, taxes, and SSNIT deductions.</p>
                <a href="<?php echo BASE_URL; ?>hr/payroll" class="btn btn-soft-success w-100 py-2">
                    Process Payroll <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
