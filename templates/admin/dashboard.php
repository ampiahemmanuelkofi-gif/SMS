<div class="row g-4">
    <!-- System Health Stats -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold">Total Users</h6>
                <div class="h3 fw-bold mb-0"><?php echo $stats['user_count']; ?></div>
                <div class="text-success small"><i class="bi bi-person-check"></i> <?php echo $stats['active_sessions']; ?> Online</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm border-start border-info border-4">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold">Database Size</h6>
                <div class="h3 fw-bold mb-0"><?php echo $stats['database_size']; ?></div>
                <div class="small text-muted">Optimized 2 days ago</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold">Storage Health</h6>
                <div class="h3 fw-bold mb-0"><?php echo $stats['disk_usage']; ?></div>
                <div class="small text-muted text-truncate">500 GB Total</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>admin/maintenance" class="card h-100 border-0 shadow-sm bg-primary text-white text-decoration-none">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-cloud-download fs-1"></i>
                <div class="fw-bold">Trigger Backup</div>
            </div>
        </a>
    </div>

    <!-- Recent System Activity -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Recent System Activity</h6>
                <a href="<?php echo BASE_URL; ?>admin/logs" class="btn btn-sm btn-link">View All Logs</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Action</th>
                                <th class="border-0">User</th>
                                <th class="border-0">Time</th>
                                <th class="border-0">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLogs as $log): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold small"><?php echo ucfirst($log['action']); ?></div>
                                        <div class="text-muted x-small"><?php echo $log['detail']; ?></div>
                                    </td>
                                    <td><?php echo $log['full_name']; ?></td>
                                    <td><?php echo date('M d, H:i', strtotime($log['created_at'])); ?></td>
                                    <td><code class="small"><?php echo $log['ip_address']; ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- System Configuration Hub -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-gear-wide-connected me-2 text-primary"></i>System Configuration Hub</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $hubs = [
                        ['icon' => 'bi-building', 'label' => 'School Info', 'tab' => 'school', 'color' => 'primary'],
                        ['icon' => 'bi-book', 'label' => 'Academic', 'tab' => 'academic', 'color' => 'info'],
                        ['icon' => 'bi-people', 'label' => 'Users & Roles', 'tab' => 'users', 'color' => 'success'],
                        ['icon' => 'bi-wallet2', 'label' => 'Finance', 'tab' => 'finance', 'color' => 'warning'],
                        ['icon' => 'bi-bell', 'label' => 'Notifications', 'tab' => 'notifications', 'color' => 'danger'],
                        ['icon' => 'bi-palette', 'label' => 'Appearance', 'tab' => 'appearance', 'color' => 'secondary'],
                        ['icon' => 'bi-shield-lock', 'label' => 'Security', 'tab' => 'security', 'color' => 'dark'],
                        ['icon' => 'bi-grid-3x3', 'label' => 'Modules', 'tab' => 'modules', 'color' => 'primary'],
                        ['icon' => 'bi-file-earmark', 'label' => 'Documents', 'tab' => 'documents', 'color' => 'info'],
                        ['icon' => 'bi-database', 'label' => 'Backup', 'tab' => 'backup', 'color' => 'warning'],
                    ];
                    foreach ($hubs as $hub): ?>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="<?php echo BASE_URL; ?>settings?tab=<?php echo $hub['tab']; ?>" class="config-tile text-center d-block p-3 rounded-3 text-decoration-none">
                                <div class="avatar avatar-md bg-soft-<?php echo $hub['color']; ?> text-<?php echo $hub['color']; ?> mx-auto mb-2">
                                    <i class="bi <?php echo $hub['icon']; ?>"></i>
                                </div>
                                <div class="small fw-semibold text-dark text-truncate"><?php echo $hub['label']; ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Modules Quick Toggles -->
    <div class="col-md-4">
        <!-- Moved content here if needed, but the hub above is more comprehensive -->
    </div>
</div>

<style>
    .x-small { font-size: 11px; }
    .config-tile {
        background: #f8fafc;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    .config-tile:hover {
        background: #ffffff;
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
</style>
