<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Admissions Management</h1>
        <p class="text-muted mb-0">Track and manage new student applications</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>admissions/apply" target="_blank" class="btn btn-soft-primary">
            <i class="bi bi-box-arrow-up-right me-1"></i> Public Application Port
        </a>
    </div>
</div>

<!-- Stats Dashboard -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-primary text-primary">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-value"><?php echo $stats['total']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Total Applications</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-warning text-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value"><?php echo $stats['pending']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Pending Review</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-info text-info">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-value"><?php echo $stats['interviews']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Interviews</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="stat-icon bg-soft-success text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value"><?php echo $stats['accepted']; ?></div>
            <div class="stat-label text-uppercase small tracking-wider">Accepted</div>
        </div>
    </div>
</div>

<!-- Filter & Admissions Table -->
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <div class="nav nav-pills nav-pills-soft gap-2">
            <a href="<?php echo BASE_URL; ?>admissions" class="nav-link btn-sm <?php echo !$filter_status ? 'active' : ''; ?>">All</a>
            <a href="<?php echo BASE_URL; ?>admissions?status=pending" class="nav-link btn-sm <?php echo $filter_status === 'pending' ? 'active' : ''; ?>">Pending</a>
            <a href="<?php echo BASE_URL; ?>admissions?status=accepted" class="nav-link btn-sm <?php echo $filter_status === 'accepted' ? 'active' : ''; ?>">Accepted</a>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Status: <?php echo $filter_status ? ucwords(str_replace('_', ' ', $filter_status)) : 'All'; ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admissions?status=rejected">Rejected</a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admissions?status=interview_scheduled">Interviewed</a></li>
            </ul>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">App ID</th>
                    <th>Applicant Name</th>
                    <th>Grade Level</th>
                    <th>Guardian</th>
                    <th>Application Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                    <tr><td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-clipboard-x display-4 opacity-25"></i>
                            <h6 class="mt-3">No applications found</h6>
                        </div>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td class="ps-4"><code>#<?php echo Security::clean($app['application_number']); ?></code></td>
                            <td>
                                <div class="fw-bold"><?php echo Security::clean($app['first_name'] . ' ' . $app['last_name']); ?></div>
                                <div class="x-small text-muted"><?php echo ucfirst($app['gender']); ?></div>
                            </td>
                            <td><span class="badge bg-soft-primary text-primary"><?php echo Security::clean($app['class_name']); ?></span></td>
                            <td>
                                <div class="small fw-semibold"><?php echo Security::clean($app['guardian_name']); ?></div>
                                <div class="x-small text-muted"><?php echo Security::clean($app['guardian_phone']); ?></div>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                            <td>
                                <?php 
                                    $statusClass = 'bg-soft-secondary text-secondary';
                                    if ($app['status'] === 'accepted') $statusClass = 'bg-soft-success text-success';
                                    if ($app['status'] === 'pending') $statusClass = 'bg-soft-warning text-warning';
                                    if ($app['status'] === 'rejected') $statusClass = 'bg-soft-danger text-danger';
                                    if ($app['status'] === 'interview_scheduled') $statusClass = 'bg-soft-info text-info';
                                ?>
                                <span class="badge rounded-pill <?php echo $statusClass; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $app['status'])); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?php echo BASE_URL; ?>admissions/view_application/<?php echo $app['id']; ?>" 
                                   class="btn btn-sm btn-white border shadow-none">
                                    <i class="bi bi-search"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
