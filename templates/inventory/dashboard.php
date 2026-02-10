<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-box-seam"></i> Asset & Inventory Dashboard</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white p-4">
            <h6 class="text-uppercase opacity-75 small fw-bold">Total Asset Value</h6>
            <h1 class="fw-bold mb-0">GHS <?php echo number_format($total_asset_value, 2); ?></h1>
            <div class="mt-3">
                <span class="badge bg-white text-primary">Active Assets</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 border-start border-4 border-warning">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-exclamation-triangle text-warning fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Low Stock Alerts</h6>
                    <h2 class="fw-bold mb-0 text-warning"><?php echo count($low_stock_items); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 border-start border-4 border-info">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-wrench-adjustable text-info fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Recent Repairs</h6>
                    <h2 class="fw-bold mb-0 text-info"><?php echo count($recent_maintenance); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Low Stock consumables</h5>
                <a href="<?php echo BASE_URL; ?>inventory/stock" class="btn btn-sm btn-outline-primary">Manage Stock</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Current</th>
                            <th>Threshold</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_items as $item): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($item['item_name']); ?></strong></td>
                                <td class="text-danger fw-bold"><?php echo $item['current_stock']; ?> <?php echo $item['unit_of_measure']; ?></td>
                                <td><?php echo $item['min_stock_level']; ?></td>
                                <td><span class="badge bg-danger">Critical</span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($low_stock_items)): ?>
                            <tr><td colspan="4" class="text-center py-5">All stock levels are healthy.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Recent Maintenance Activity</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_maintenance as $log): ?>
                        <div class="list-group-item p-3 border-start border-4 border-info mb-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-1"><?php echo Security::clean($log['asset_name']); ?></h6>
                                <small class="text-muted"><?php echo date('M d', strtotime($log['maintenance_date'])); ?></small>
                            </div>
                            <p class="small text-muted mb-1"><?php echo Security::clean($log['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark"><?php echo ucfirst($log['maintenance_type']); ?></span>
                                <small class="fw-bold text-primary">GHS <?php echo number_format($log['cost'], 2); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($recent_maintenance)): ?>
                        <div class="p-5 text-center text-muted">No maintenance recorded recently.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center py-3">
                <a href="<?php echo BASE_URL; ?>inventory/maintenance" class="small text-decoration-none">View All Maintenance Logs</a>
            </div>
        </div>
    </div>
</div>
