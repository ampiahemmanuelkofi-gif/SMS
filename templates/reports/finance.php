<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title"><i class="bi bi-cash-coin"></i> Financial Reports</h2>
            <p class="text-muted">Fee collection summary and transaction history.</p>
        </div>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Report
        </button>
    </div>
</div>

<div class="table-card mb-4 no-print">
    <form action="<?php echo BASE_URL; ?>reports/finance" method="GET" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter Records</button>
        </div>
    </form>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="table-card border-0 shadow-sm border-start border-4 border-primary">
            <h5>Collection Summary</h5>
            <div class="row g-3 mt-1">
                <?php 
                $grandTotal = 0;
                foreach ($summary as $s): 
                    $grandTotal += $s['total'];
                ?>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <small class="text-muted d-block"><?php echo strtoupper(str_replace('_', ' ', $s['payment_method'])); ?></small>
                            <span class="h4 mb-0 fw-bold">GH₵ <?php echo number_format($s['total'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-3">
                    <div class="p-3 bg-primary text-white rounded shadow-sm">
                        <small class="text-white-50 d-block">GRAND TOTAL</small>
                        <span class="h4 mb-0 fw-bold">GH₵ <?php echo number_format($grandTotal, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-card">
    <h5>Collection Details (<?php echo date('d M Y', strtotime($filters['start_date'])); ?> - <?php echo date('d M Y', strtotime($filters['end_date'])); ?>)</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mt-3">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th class="text-end">Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($p['payment_date'])); ?></td>
                        <td><?php echo Security::clean($p['first_name'] . ' ' . $p['last_name']); ?></td>
                        <td><?php echo Security::clean($p['class_name']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo Security::clean($p['payment_method']); ?></span></td>
                        <td><small><?php echo Security::clean($p['reference_number']); ?></small></td>
                        <td class="text-end fw-bold text-success">GH₵ <?php echo number_format($p['amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
