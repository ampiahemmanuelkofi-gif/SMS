<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-building"></i> Hostel Management Dashboard</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <?php foreach ($hostels as $h): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-house-door text-primary fs-3"></i>
                        </div>
                        <span class="badge bg-<?php echo ($h['hostel_type'] == 'boys' ? 'info' : ($h['hostel_type'] == 'girls' ? 'danger' : 'success')); ?>">
                            <?php echo ucfirst($h['hostel_type']); ?>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo Security::clean($h['hostel_name']); ?></h5>
                    <p class="text-muted small mb-3"><?php echo Security::clean($h['description'] ?? 'No description provided.'); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-muted">Occupancy</span>
                        <span class="small fw-bold">85%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="mt-3 text-end">
                        <small class="text-muted">Capacity: <?php echo $h['capacity']; ?> Beds</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100 border-dashed bg-light d-flex align-items-center justify-content-center p-4">
            <a href="<?php echo BASE_URL; ?>hostel/inventory" class="text-decoration-none text-center">
                <i class="bi bi-plus-circle fs-1 text-primary mb-2"></i>
                <h6 class="text-primary fw-bold mb-0">Add New Hostel</h6>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Allocations</h5>
                <a href="<?php echo BASE_URL; ?>hostel/allocations" class="btn btn-sm btn-primary">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Hall/Room</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($allocations, 0, 5) as $a): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo Security::clean($a['student_name']); ?></div>
                                    <small class="text-muted">Bed: <?php echo $a['bed_number']; ?></small>
                                </td>
                                <td>
                                    <div><?php echo Security::clean($a['hostel_name']); ?></div>
                                    <small class="text-muted">Room: <?php echo $a['room_number']; ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($a['allotted_on'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($allocations)): ?>
                            <tr><td colspan="3" class="text-center py-4">No recent allocations.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Pending Leave Requests</h5>
                <a href="<?php echo BASE_URL; ?>hostel/leave" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($pending_leave as $l): ?>
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><?php echo Security::clean($l['student_name']); ?></h6>
                                <span class="badge bg-warning text-dark"><?php echo ucfirst($l['leave_type']); ?></span>
                            </div>
                            <p class="small text-muted mb-2"><?php echo Security::clean($l['reason']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> <?php echo date('M d', strtotime($l['out_date'])); ?></small>
                                <div class="btn-group">
                                    <form action="<?php echo BASE_URL; ?>hostel/leave" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $l['id']; ?>">
                                        <input type="hidden" name="action" value="status">
                                        <button name="status" value="approved" class="btn btn-sm btn-success p-1 py-0"><i class="bi bi-check"></i></button>
                                        <button name="status" value="rejected" class="btn btn-sm btn-danger p-1 py-0"><i class="bi bi-x"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($pending_leave)): ?>
                        <div class="p-4 text-center text-muted">No pending requests.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
