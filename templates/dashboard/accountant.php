<?php
$currency = setting('currency_symbol', 'GH₵');
$collectionRate = $stats['total_expected'] > 0 
    ? round(($stats['total_collected'] / $stats['total_expected']) * 100, 1) 
    : 0;

// Format numbers
if (!function_exists('formatMoney')) {
    function formatMoney($amount, $symbol = 'GH₵') {
        return $symbol . ' ' . number_format($amount, 2);
    }
}
?>

<div class="mb-4">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="page-title mb-1"><i class="bi bi-wallet2 me-2 text-primary"></i>Financial Overview</h1>
            <p class="text-muted mb-0">Academic Term: <strong><?php echo $currentTerm['name']; ?></strong> | Reporting for today.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="d-flex gap-2 justify-content-md-end">
                <a href="<?php echo BASE_URL; ?>fees/collect" class="btn btn-primary shadow-sm">
                    <i class="bi bi-cash-stack me-2"></i>Record Payment
                </a>
                <a href="<?php echo BASE_URL; ?>reports/fees" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Reports
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Total Collected</p>
                        <h3 class="fw-bold mb-0"><?php echo formatMoney($stats['total_collected'], $currency); ?></h3>
                        <p class="mb-0 small opacity-75">Current term revenue</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Total Expected</p>
                        <h3 class="fw-bold mb-0"><?php echo formatMoney($stats['total_expected'], $currency); ?></h3>
                        <p class="mb-0 small opacity-75">Billed this term</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-calculator fs-4"></i>
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
                        <p class="mb-1 opacity-75 small text-uppercase fw-bold">Outstanding</p>
                        <h3 class="fw-bold mb-0"><?php echo formatMoney($stats['total_pending'], $currency); ?></h3>
                        <p class="mb-0 small opacity-75">Pending collection</p>
                    </div>
                    <div class="avatar avatar-lg bg-white bg-opacity-25">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-4 pe-4 border-end">
                        <small class="text-muted d-block text-uppercase fw-bold x-small mb-1">Collection Rate</small>
                        <h4 class="fw-bold mb-0 <?php echo $collectionRate >= 80 ? 'text-success' : 'text-primary'; ?>">
                            <?php echo $collectionRate; ?>%
                        </h4>
                    </div>
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar <?php echo $collectionRate >= 80 ? 'bg-success' : 'bg-primary'; ?>" 
                                 style="width: <?php echo $collectionRate; ?>%"></div>
                        </div>
                    </div>
                    <div class="ms-4 ps-4 border-start">
                        <small class="text-muted d-block text-uppercase fw-bold x-small mb-1">Today's Count</small>
                        <h4 class="fw-bold mb-0 text-info"><?php echo $stats['payments_today']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 d-flex justify-content-center">
            <div class="card-body py-2 d-flex align-items-center justify-content-around">
                <a href="<?php echo BASE_URL; ?>fees/balances" class="text-decoration-none text-center">
                    <div class="text-warning mb-1"><i class="bi bi-person-x fs-4"></i></div>
                    <div class="x-small fw-bold text-dark">Defaulters</div>
                </a>
                <a href="<?php echo BASE_URL; ?>fees/structure" class="text-decoration-none text-center">
                    <div class="text-info mb-1"><i class="bi bi-list-columns fs-4"></i></div>
                    <div class="x-small fw-bold text-dark">Structure</div>
                </a>
                <a href="<?php echo BASE_URL; ?>fees/categories" class="text-decoration-none text-center">
                    <div class="text-secondary mb-1"><i class="bi bi-tags fs-4"></i></div>
                    <div class="x-small fw-bold text-dark">Categories</div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Payments -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Recent Fee Payments</h6>
                <a href="<?php echo BASE_URL; ?>fees/invoices" class="btn btn-sm btn-link text-decoration-none">View All History</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">Date</th>
                                <th class="border-0">Student</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Method</th>
                                <th class="border-0">Reference</th>
                                <th class="border-0 text-end px-4">Processed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentPayments)): ?>
                                <?php foreach ($recentPayments as $payment): ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold text-dark"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></div>
                                            <div class="x-small text-muted"><?php echo date('h:i A', strtotime($payment['created_at'])); ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark"><?php echo Security::clean($payment['first_name'] . ' ' . $payment['last_name']); ?></div>
                                            <div class="x-small text-muted"><?php echo Security::clean($payment['student_id']); ?></div>
                                        </td>
                                        <td><span class="fw-bold text-success"><?php echo formatMoney($payment['amount'], $currency); ?></span></td>
                                        <td>
                                            <span class="badge bg-soft-primary text-primary px-2 py-1">
                                                <?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?>
                                            </span>
                                        </td>
                                        <td><code class="text-muted"><?php echo Security::clean($payment['reference_number']); ?></code></td>
                                        <td class="text-end px-4 text-muted small"><?php echo Security::clean($payment['received_by_name']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                        No payments recorded yet
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-gradient-primary { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.1); }
    .x-small { font-size: 0.75rem; }
</style>
