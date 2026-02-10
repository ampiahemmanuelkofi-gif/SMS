<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-file-earmark-text"></i> Fee Invoices</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateModal">
            <i class="bi bi-lightning-charge"></i> Batch Generate Invoices
        </button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>fees/invoices" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Select Term</label>
                <select name="term_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($terms as $t): ?>
                        <option value="<?php echo $t['id']; ?>" <?php echo $termId == $t['id'] ? 'selected' : ''; ?>>
                            <?php echo $t['year_name'] . ' - ' . $t['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Student</th>
                    <th>Total Amout</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><code><?php echo Security::clean($inv['invoice_number']); ?></code></td>
                        <td><?php echo Security::clean($inv['first_name'] . ' ' . $inv['last_name']); ?> (<?php echo $inv['student_id']; ?>)</td>
                        <td><strong>GH₵ <?php echo number_format($inv['total_amount'], 2); ?></strong></td>
                        <td>GH₵ <?php echo number_format($inv['paid_amount'], 2); ?></td>
                        <td>
                            <?php if ($inv['status'] == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php elseif ($inv['status'] == 'partially_paid'): ?>
                                <span class="badge bg-warning">Partially Paid</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($inv['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>fees/generateInvoices" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header"><h5>Batch Generate Invoices</h5></div>
                <div class="modal-body">
                    <p>This will generate invoices for all active students for the selected term based on the current fee structure.</p>
                    <div class="mb-3">
                        <label class="form-label">Term</label>
                        <select name="term_id" class="form-select" required>
                            <?php foreach ($terms as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo $t['year_name'] . ' - ' . $t['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Generate Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
