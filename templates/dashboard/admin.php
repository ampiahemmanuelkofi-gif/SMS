<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Welcome back, <?php echo Security::clean(Auth::getFullName()); ?> 👋</h4>
        <p class="text-muted small mb-0">Here's what's happening at <?php echo SCHOOL_NAME; ?> today.</p>
    </div>
    <div class="text-end">
        <span class="badge bg-soft-primary text-primary px-3 py-2">
            <i class="bi bi-calendar3 me-1"></i>
            <?php echo date('l, F j, Y'); ?>
        </span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-soft-primary text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_students']); ?></div>
                <div class="stat-label">Total Students</div>
                <div class="mt-2 small">
                    <span class="text-success"><i class="bi bi-arrow-up"></i> Active</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-soft-success text-success">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_teachers']); ?></div>
                <div class="stat-label">Total Teachers</div>
                <div class="mt-2 small">
                    <span class="text-muted">Academic Staff</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-soft-warning text-warning">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_classes']); ?></div>
                <div class="stat-label">Total Classes</div>
                <div class="mt-2 small">
                    <span class="text-muted">Academic Levels</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="stat-icon bg-soft-danger text-danger">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_sections']); ?></div>
                <div class="stat-label">Total Sections</div>
                <div class="mt-2 small">
                    <span class="text-muted">Per Class</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Current Term Banner -->
<div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 mb-4" style="border-radius: var(--radius-lg);">
    <div class="avatar bg-info text-white">
        <i class="bi bi-calendar-range"></i>
    </div>
    <div class="flex-grow-1">
        <strong>Current Academic Session:</strong>
        <?php echo $currentTerm ? Security::clean($currentTerm['name']) . ' (' . date('M d, Y', strtotime($currentTerm['start_date'])) . ' - ' . date('M d, Y', strtotime($currentTerm['end_date'])) . ')' : 'No active term configured'; ?>
    </div>
    <a href="<?php echo BASE_URL; ?>academics/periods" class="btn btn-sm btn-info text-white">
        <i class="bi bi-gear me-1"></i> Manage
    </a>
</div>

<div class="row g-4">
    <!-- Recent Students -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill text-primary me-2"></i>Recently Admitted</h6>
                <a href="<?php echo BASE_URL; ?>students" class="btn btn-soft-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Admitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentStudents)): ?>
                                <?php foreach ($recentStudents as $student): ?>
                                    <tr>
                                        <td><code class="small"><?php echo Security::clean($student['student_id']); ?></code></td>
                                        <td class="fw-medium"><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                        <td><span class="badge bg-soft-primary text-primary"><?php echo Security::clean($student['class_name'] . ' ' . $student['section_name']); ?></span></td>
                                        <td class="text-muted small"><?php echo date('M d', strtotime($student['admission_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="table-empty">
                                        <i class="bi bi-inbox"></i>
                                        <p>No recent admissions</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Notices -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone-fill text-warning me-2"></i>Recent Notices</h6>
                <a href="<?php echo BASE_URL; ?>notices" class="btn btn-soft-primary btn-sm">View All</a>
            </div>
            <div class="card-body">
                <?php if (!empty($notices)): ?>
                    <?php foreach ($notices as $notice): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="avatar bg-soft-warning text-warning flex-shrink-0">
                                <i class="bi bi-bell"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-medium"><?php echo Security::clean($notice['title']); ?></h6>
                                <p class="mb-1 small text-muted"><?php echo Security::clean(substr($notice['content'], 0, 80)) . '...'; ?></p>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i><?php echo Security::clean($notice['posted_by_name']); ?>
                                    <span class="mx-1">•</span>
                                    <i class="bi bi-clock me-1"></i><?php echo date('M d', strtotime($notice['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-megaphone"></i></div>
                        <p class="empty-state-title">No notices yet</p>
                        <a href="<?php echo BASE_URL; ?>notices/add" class="btn btn-sm btn-primary">Post First Notice</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-transparent">
        <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions</h6>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo BASE_URL; ?>students/add" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Add Student
            </a>
            <a href="<?php echo BASE_URL; ?>attendance/mark" class="btn btn-success">
                <i class="bi bi-calendar-check-fill me-1"></i> Mark Attendance
            </a>
            <a href="<?php echo BASE_URL; ?>fees/payment" class="btn btn-outline-primary">
                <i class="bi bi-cash-stack me-1"></i> Record Payment
            </a>
            <a href="<?php echo BASE_URL; ?>notices/add" class="btn btn-outline-warning">
                <i class="bi bi-megaphone-fill me-1"></i> Post Notice
            </a>
            <a href="<?php echo BASE_URL; ?>reports" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Generate Reports
            </a>
        </div>
    </div>
</div>
