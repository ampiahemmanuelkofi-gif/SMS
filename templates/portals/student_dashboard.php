<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title text-primary"><i class="bi bi-mortarboard-fill"></i> Welcome, <?php echo $_SESSION['full_name']; ?></h2>
        <p class="text-muted">Here is your academic overview for today.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Attendance card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle bg-success-subtle p-3 me-3">
                    <i class="bi bi-calendar-check text-success fs-3"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted small mb-1">Attendance</h6>
                    <h2 class="mb-0 fw-bold"><?php echo $attendance_rate; ?></h2>
                </div>
            </div>
            <div class="bg-success py-1"></div>
        </div>
    </div>

    <!-- Recent Grade card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle bg-primary-subtle p-3 me-3">
                    <i class="bi bi-award text-primary fs-3"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted small mb-1">Latest Grade</h6>
                    <h2 class="mb-0 fw-bold"><?php echo $recent_grades[0]['grade']; ?></h2>
                </div>
            </div>
            <div class="bg-primary py-1"></div>
        </div>
    </div>

    <!-- Library card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle bg-warning-subtle p-3 me-3">
                    <i class="bi bi-book text-warning fs-3"></i>
                </div>
                <div>
                    <h6 class="text-uppercase text-muted small mb-1">Books Due</h6>
                    <h2 class="mb-0 fw-bold"><?php echo count($library_books); ?></h2>
                </div>
            </div>
            <div class="bg-warning py-1"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Academic Performance -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Recent Academic Results</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Score/Grade</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_grades as $grade): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($grade['subject']); ?></strong></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info fs-6">
                                        <?php echo $grade['grade']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($grade['date'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary shadow-sm">View Details</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Latest Updates</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($announcements as $a): ?>
                        <div class="list-group-item p-3">
                            <h6 class="fw-bold mb-1"><?php echo Security::clean($a['title']); ?></h6>
                            <p class="small text-muted mb-2"><?php echo substr(Security::clean($a['content']), 0, 100); ?>...</p>
                            <small class="text-primary"><?php echo date('M d', strtotime($a['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-3 text-center border-top">
                    <a href="<?php echo BASE_URL; ?>communication" class="btn btn-sm btn-light">View All Notice Board</a>
                </div>
            </div>
        </div>
    </div>
</div>
