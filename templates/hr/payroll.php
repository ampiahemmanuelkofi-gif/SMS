<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1"><i class="bi bi-cash-stack me-2 text-primary"></i>Payroll Processing</h1>
        <p class="text-muted mb-0">Manage monthly staff salaries and tax deductions for <strong><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <form action="<?php echo BASE_URL; ?>hr/payroll" method="POST" class="d-inline">
            <input type="hidden" name="action" value="generate">
            <input type="hidden" name="month" value="<?php echo $month; ?>">
            <input type="hidden" name="year" value="<?php echo $year; ?>">
            <button type="submit" class="btn btn-success shadow-sm">
                <i class="bi bi-lightning-charge me-1"></i> Run Processing
            </button>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 bg-light">
    <div class="card-body p-3">
        <form method="GET" action="<?php echo BASE_URL; ?>hr/payroll" class="row g-2 align-items-center">
            <div class="col-md-auto">
                <span class="text-muted small fw-bold text-uppercase ms-2">Select Period:</span>
            </div>
            <div class="col-md-2">
                <select name="month" class="form-select border-0 shadow-none bg-white" onchange="this.form.submit()">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo $month == $m ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="year" class="form-select border-0 shadow-none bg-white" onchange="this.form.submit()">
                    <option value="<?php echo date('Y'); ?>" <?php echo $year == date('Y') ? 'selected' : ''; ?>><?php echo date('Y'); ?></option>
                    <option value="<?php echo date('Y')-1; ?>" <?php echo $year == (date('Y')-1) ? 'selected' : ''; ?>><?php echo date('Y')-1; ?></option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Employee</th>
                    <th class="text-end">Basic Salary</th>
                    <th class="text-end">SSNIT (5.5%)</th>
                    <th class="text-end">Income Tax</th>
                    <th class="text-end">Net Salary</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payrolls as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo Security::clean($p['full_name']); ?></div>
                            <code class="x-small"><?php echo Security::clean($p['emp_code']); ?></code>
                        </td>
                        <td class="text-end fw-medium">GH₵ <?php echo number_format($p['basic_salary'], 2); ?></td>
                        <td class="text-end text-danger small">- GH₵ <?php echo number_format($p['ssnit_employee'], 2); ?></td>
                        <td class="text-end text-danger small">- GH₵ <?php echo number_format($p['income_tax'], 2); ?></td>
                        <td class="text-end text-success fw-bold">GH₵ <?php echo number_format($p['net_salary'], 2); ?></td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?php echo $p['status'] === 'paid' ? 'bg-soft-success text-success' : 'bg-soft-primary text-primary'; ?>">
                                <?php echo ucfirst($p['status']); ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo BASE_URL; ?>hr/payslip/<?php echo $p['id']; ?>" class="btn btn-sm btn-white border shadow-none">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Payslip
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($payrolls)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-cash-stack display-4 opacity-25"></i>
                                <h6 class="mt-3">No payroll logs for this period</h6>
                                <p class="text-muted small">Run processing to generate logs for <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
