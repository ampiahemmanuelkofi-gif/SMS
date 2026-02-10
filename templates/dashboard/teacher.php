<div class="mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-1"><i class="bi bi-mortarboard-fill me-2 text-primary"></i>Teacher Dashboard</h1>
            <p class="text-muted mb-0">Welcome back, <strong><?php echo Auth::getFullName(); ?></strong>! Here's your class overview for today.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="<?php echo BASE_URL; ?>attendance/mark" class="btn btn-success shadow-sm">
                <i class="bi bi-calendar-check-fill me-2"></i>Mark Attendance
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">My Classes</p>
                        <h3 class="fw-bold mb-0"><?php echo count($assignedClasses); ?></h3>
                        <p class="mb-0 small opacity-75">Assigned sections</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Pending Marks</p>
                        <h3 class="fw-bold mb-0"><?php echo $pendingMarks; ?></h3>
                        <p class="mb-0 small opacity-75">Students requiring entry</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-clipboard-x-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Active Homework</p>
                        <h3 class="fw-bold mb-0"><?php echo count($homework); ?></h3>
                        <p class="mb-0 small opacity-75">Recent assignments</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Assigned Classes -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-book-fill me-2 text-primary"></i>My Assigned Classes</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">Class & Section</th>
                                <th class="border-0">Level</th>
                                <th class="border-0">Students</th>
                                <th class="border-0 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($assignedClasses)): ?>
                                <?php foreach ($assignedClasses as $class): ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold text-dark"><?php echo Security::clean($class['class_name'] . ' ' . $class['section_name']); ?></div>
                                            <div class="text-muted small">Academic Term 2024/25</div>
                                        </td>
                                        <td><span class="badge bg-soft-info text-info"><?php echo ucfirst($class['level']); ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-people me-2 text-muted"></i>
                                                <?php echo $class['student_count']; ?>
                                            </div>
                                        </td>
                                        <td class="text-end px-4">
                                            <a href="<?php echo BASE_URL; ?>attendance/mark/<?php echo $class['id']; ?>" 
                                               class="btn btn-sm btn-soft-success me-1" title="Mark Attendance">
                                                <i class="bi bi-calendar-check"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>assessments/entry/<?php echo $class['id']; ?>" 
                                               class="btn btn-sm btn-soft-primary" title="Enter Marks">
                                                <i class="bi bi-clipboard-data"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                        No classes assigned yet
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?php echo BASE_URL; ?>assessments/entry" class="quick-link-tile shadow-none">
                            <i class="bi bi-clipboard-data text-primary"></i>
                            <span>Enter Marks</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?php echo BASE_URL; ?>homework/create" class="quick-link-tile shadow-none">
                            <i class="bi bi-journal-plus text-success"></i>
                            <span>Add Homework</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?php echo BASE_URL; ?>reports/class" class="quick-link-tile shadow-none">
                            <i class="bi bi-file-earmark-bar-graph text-info"></i>
                            <span>Class Reports</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?php echo BASE_URL; ?>timetable" class="quick-link-tile shadow-none">
                            <i class="bi bi-calendar3 text-danger"></i>
                            <span>Timetable</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Homework Snippet -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2 text-success"></i>Recent Homework</h6>
                <a href="<?php echo BASE_URL; ?>homework" class="small text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($homework)): ?>
                        <?php foreach (array_slice($homework, 0, 4) as $hw): ?>
                            <div class="list-group-item px-4 border-0 mb-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate" style="max-width: 70%;">
                                        <div class="fw-bold text-dark text-truncate"><?php echo Security::clean($hw['title']); ?></div>
                                        <div class="text-muted small"><?php echo Security::clean($hw['subject_name']); ?> • <?php echo Security::clean($hw['class_name']); ?></div>
                                    </div>
                                    <div class="text-end">
                                        <?php
                                        $dueDate = strtotime($hw['due_date']);
                                        $today = strtotime(date('Y-m-d'));
                                        if ($dueDate < $today) {
                                            echo '<span class="badge bg-soft-danger text-danger">Overdue</span>';
                                        } elseif ($dueDate == $today) {
                                            echo '<span class="badge bg-soft-warning text-warning">Today</span>';
                                        } else {
                                            echo '<span class="badge bg-soft-success text-success">Active</span>';
                                        }
                                        ?>
                                        <div class="x-small text-muted mt-1"><?php echo date('M d', $dueDate); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted small">No homework assignments yet</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%); }
    
    .bg-soft-info { background-color: rgba(76, 201, 240, 0.1); }
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
    .btn-soft-success { background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: none; }
    .btn-soft-success:hover { background-color: #10b981; color: white; }
    .btn-soft-primary { background-color: rgba(67, 97, 238, 0.1); color: #4361ee; border: none; }
    .btn-soft-primary:hover { background-color: #4361ee; color: white; }
    
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    
    .quick-link-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        background: #f8fafc;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #edf2f7;
    }
    .quick-link-tile:hover {
        background: #fff;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
        border-color: #e2e8f0;
    }
    .quick-link-tile i { font-size: 1.5rem; margin-bottom: 0.5rem; }
    .quick-link-tile span { font-size: 0.8rem; font-weight: 600; color: #475569; }
</style>
