<div class="container py-5">
    <div class="card border-0 shadow-sm overflow-hidden" id="payslipContent">
        <div class="card-header bg-dark text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0"><?php echo SCHOOL_NAME; ?></h4>
                    <small>Official Staff Payslip</small>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="mb-0">PERIOD: <?php echo date('F Y', mktime(0, 0, 0, $p['month'], 1, $p['year'])); ?></h5>
                    <small>Payslip #<?php echo str_pad($p['id'], 6, '0', STR_PAD_LEFT); ?></small>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase small mb-2">Employee Information</h6>
                    <table class="table table-sm table-borderless">
                        <tr><td width="150">Name:</td><td><strong><?php echo Security::clean($p['full_name']); ?></strong></td></tr>
                        <tr><td>Employee ID:</td><td><code><?php echo Security::clean($p['emp_code']); ?></code></td></tr>
                        <tr><td>Department:</td><td><?php echo Security::clean($p['department_name']); ?></td></tr>
                        <tr><td>Designation:</td><td><?php echo Security::clean($p['designation']); ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase small mb-2">Payment Details</h6>
                    <table class="table table-sm table-borderless">
                        <tr><td width="150">Bank:</td><td><?php echo Security::clean($p['bank_name']); ?></td></tr>
                        <tr><td>Account #:</td><td><?php echo Security::clean($p['account_number']); ?></td></tr>
                        <tr><td>Status:</td><td><span class="badge bg-success"><?php echo ucfirst($p['status']); ?></span></td></tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="bg-light p-2 mb-3 fw-bold">Earnings</h6>
                    <table class="table table-sm">
                        <tr><td>Basic Salary</td><td class="text-end">GH₵ <?php echo number_format($p['basic_salary'], 2); ?></td></tr>
                        <tr><td>Allowances</td><td class="text-end">GH₵ <?php echo number_format($p['allowances'], 2); ?></td></tr>
                        <tr class="fw-bold fs-5"><td>Gross Salary</td><td class="text-end">GH₵ <?php echo number_format($p['basic_salary'] + $p['allowances'], 2); ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="bg-light p-2 mb-3 fw-bold">Deductions</h6>
                    <table class="table table-sm">
                        <tr><td>SSNIT (5.5%)</td><td class="text-end">- GH₵ <?php echo number_format($p['ssnit_employee'], 2); ?></td></tr>
                        <tr><td>Income Tax (PAYE)</td><td class="text-end">- GH₵ <?php echo number_format($p['income_tax'], 2); ?></td></tr>
                        <tr><td>Other Deductions</td><td class="text-end">- GH₵ <?php echo number_format($p['deductions'], 2); ?></td></tr>
                        <tr class="fw-bold fs-5 text-danger"><td>Total Deductions</td><td class="text-end">- GH₵ <?php echo number_format($p['ssnit_employee'] + $p['income_tax'] + $p['deductions'], 2); ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="mt-4 p-4 bg-success-subtle rounded d-flex justify-content-between align-items-center">
                <h4 class="mb-0">NET PAYABLE</h4>
                <h2 class="mb-0 text-success">GH₵ <?php echo number_format($p['net_salary'], 2); ?></h2>
            </div>
            
            <div class="mt-5 text-center text-muted small">
                <p>This is a computer-generated document and does not require a physical signature.</p>
                <p>&copy; <?php echo date('Y'); ?> <?php echo SCHOOL_NAME; ?> - HR Powered by Antigravity</p>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-center no-print">
        <button class="btn btn-primary px-4" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Payslip
        </button>
        <a href="<?php echo BASE_URL; ?>hr/payroll" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left"></i> Back to Payroll
        </a>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    .sidebar, .navbar { display: none !important; }
    body { background: white !important; }
    #payslipContent { border: 1px solid #ddd !important; box-shadow: none !important; }
}
</style>
