<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-graph-up"></i> Profit & Loss Statement</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
            <button class="btn btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Export</button>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>reports/pnl" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter Report</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Income Section -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Income</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <?php foreach ($pnl['income'] as $row): ?>
                            <tr>
                                <td><?php echo Security::clean($row['name']); ?></td>
                                <td class="text-end">GH₵ <?php echo number_format($row['balance'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pnl['income'])): ?>
                            <tr><td colspan="2" class="text-center text-muted py-3">No income recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td>Total Income</td>
                            <td class="text-end">GH₵ <?php echo number_format(array_sum(array_column($pnl['income'], 'balance')), 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Expenses Section -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">Expenses</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <?php foreach ($pnl['expenses'] as $row): ?>
                            <tr>
                                <td><?php echo Security::clean($row['name']); ?></td>
                                <td class="text-end">GH₵ <?php echo number_format($row['balance'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pnl['expenses'])): ?>
                            <tr><td colspan="2" class="text-center text-muted py-3">No expenses recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td>Total Expenses</td>
                            <td class="text-end">GH₵ <?php echo number_format(array_sum(array_column($pnl['expenses'], 'balance')), 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body d-flex justify-content-between align-items-center <?php echo $pnl['net_profit'] >= 0 ? 'bg-light' : 'bg-danger-subtle'; ?>">
        <h4 class="mb-0">Net Profit / (Loss)</h4>
        <h3 class="mb-0 <?php echo $pnl['net_profit'] >= 0 ? 'text-success' : 'text-danger'; ?>">
            GH₵ <?php echo number_format($pnl['net_profit'], 2); ?>
        </h3>
    </div>
</div>
