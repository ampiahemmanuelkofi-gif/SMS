<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-speedometer2"></i> Transport Overview</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Total Vehicles</h6>
                    <h2 class="mb-0 fw-bold"><?php echo count($vehicles); ?></h2>
                </div>
                <i class="bi bi-truck fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Active Routes</h6>
                    <h2 class="mb-0 fw-bold"><?php echo count($routes); ?></h2>
                </div>
                <i class="bi bi-map fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Maintenance Alerts</h6>
                    <h2 class="mb-0 fw-bold">0</h2>
                </div>
                <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Fleet Status</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $v): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($v['plate_number']); ?></strong></td>
                                <td><?php echo Security::clean($v['driver_name']); ?></td>
                                <td>
                                    <span class="badge <?php echo $v['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($v['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Recent Assignments</h5>
            </div>
            <div class="p-3">
                <p class="text-muted small">Route coverage and student load details appearing here...</p>
                <a href="<?php echo BASE_URL; ?>transport/assignments" class="btn btn-sm btn-outline-primary">Manage Assignments</a>
            </div>
        </div>
    </div>
</div>
