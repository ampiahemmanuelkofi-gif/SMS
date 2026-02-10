<?php
/**
 * Finance Dashboard
 * Comprehensive financial overview with stats, charts, and quick actions
 */

$stats = $stats ?? [];
$currency = setting('currency_symbol', 'GH₵');

// Format numbers
function formatMoney($amount, $symbol = 'GH₵') {
    return $symbol . number_format($amount, 2);
}
?>

<div class="row g-4">
    <!-- Summary Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Total Collected</p>
                        <h3 class="fw-bold mb-0"><?php echo formatMoney($stats['total_collected'] ?? 0, $currency); ?></h3>
                        <p class="mb-0 small opacity-75">All time revenue</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Outstanding</p>
                        <h3 class="fw-bold mb-0"><?php echo formatMoney($stats['total_outstanding'] ?? 0, $currency); ?></h3>
                        <p class="mb-0 small opacity-75">Pending payments</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Collection Rate</p>
                        <h3 class="fw-bold mb-0"><?php echo $stats['collection_rate'] ?? 0; ?>%</h3>
                        <p class="mb-0 small opacity-75"><?php echo ($stats['paid_invoices'] ?? 0) . '/' . ($stats['total_invoices'] ?? 0); ?> invoices paid</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Defaulters</p>
                        <h3 class="fw-bold mb-0"><?php echo $stats['defaulters_count'] ?? 0; ?></h3>
                        <p class="mb-0 small opacity-75">Overdue accounts</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats Row -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="row text-center">
                    <div class="col-md-4 border-end">
                        <p class="text-muted small mb-1">Today's Collections</p>
                        <h4 class="fw-bold text-success mb-0"><?php echo formatMoney($stats['today_collected'] ?? 0, $currency); ?></h4>
                    </div>
                    <div class="col-md-4 border-end">
                        <p class="text-muted small mb-1">This Week</p>
                        <h4 class="fw-bold text-primary mb-0"><?php echo formatMoney($stats['week_collected'] ?? 0, $currency); ?></h4>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted small mb-1">This Month</p>
                        <h4 class="fw-bold text-info mb-0"><?php echo formatMoney($stats['month_collected'] ?? 0, $currency); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>fees/collect" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-success text-success mx-auto mb-2">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="small fw-semibold text-dark">Collect Fees</div>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>fees/invoices" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-primary text-primary mx-auto mb-2">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div class="small fw-semibold text-dark">View Invoices</div>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>fees/structure" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-info text-info mx-auto mb-2">
                                <i class="bi bi-list-columns-reverse"></i>
                            </div>
                            <div class="small fw-semibold text-dark">Fee Structure</div>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>fees/defaulters" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-danger text-danger mx-auto mb-2">
                                <i class="bi bi-person-x"></i>
                            </div>
                            <div class="small fw-semibold text-dark">Defaulters</div>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>fees/categories" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-secondary text-secondary mx-auto mb-2">
                                <i class="bi bi-tags"></i>
                            </div>
                            <div class="small fw-semibold text-dark">Categories</div>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <a href="<?php echo BASE_URL; ?>settings?tab=finance" class="quick-action-tile text-center d-block p-3 rounded-3 text-decoration-none">
                            <div class="avatar avatar-md bg-soft-warning text-warning mx-auto mb-2">
                                <i class="bi bi-gear"></i>
                            </div>
                            <div class="small fw-semibold text-dark">Settings</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Transactions</h6>
                <a href="<?php echo BASE_URL; ?>fees/invoices" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Student</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Method</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Received By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentPayments)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No recent payments</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentPayments as $payment): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-medium"><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($payment['student_code']); ?></div>
                                        </td>
                                        <td class="fw-bold text-success"><?php echo formatMoney($payment['amount'], $currency); ?></td>
                                        <td><span class="badge bg-soft-primary text-primary"><?php echo ucfirst($payment['payment_method'] ?? 'Cash'); ?></span></td>
                                        <td><?php echo date('M d, H:i', strtotime($payment['payment_date'])); ?></td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($payment['received_by_name']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Collection by Category -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-info"></i>By Category</h6>
            </div>
            <div class="card-body">
                <?php if (empty($categoryData)): ?>
                    <p class="text-muted text-center py-4">No category data available</p>
                <?php else: ?>
                    <?php 
                    $maxTotal = max(array_column($categoryData, 'total'));
                    foreach ($categoryData as $cat): 
                        $percent = $maxTotal > 0 ? ($cat['total'] / $maxTotal) * 100 : 0;
                    ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small fw-medium"><?php echo htmlspecialchars($cat['category']); ?></span>
                                <span class="small text-muted"><?php echo formatMoney($cat['total'], $currency); ?></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: <?php echo $percent; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    
    .quick-action-tile {
        background: #f8fafc;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    .quick-action-tile:hover {
        background: #ffffff;
        border-color: var(--bs-primary);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.1); }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1); }
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    .bg-soft-secondary { background-color: rgba(100, 116, 139, 0.1); }
</style>
