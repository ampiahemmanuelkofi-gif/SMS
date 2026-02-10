<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-exclamation-triangle"></i> Fee Defaulters</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary"><i class="bi bi-printer"></i> Print List</button>
            <button class="btn btn-outline-danger"><i class="bi bi-envelope"></i> Send Bulk Reminders</button>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>fees/defaulters" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Term</label>
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
                    <th>Student Name</th>
                    <th>ID</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Balance Due</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($defaulters)): ?>
                    <tr><td colspan="6" class="text-center py-4">No defaulters found for this term.</td></tr>
                <?php else: ?>
                    <?php foreach ($defaulters as $d): ?>
                        <tr>
                            <td><?php echo Security::clean($d['first_name'] . ' ' . $d['last_name']); ?></td>
                            <td><code><?php echo Security::clean($d['student_id']); ?></code></td>
                            <td>GH₵ <?php echo number_format($d['total_amount'], 2); ?></td>
                            <td>GH₵ <?php echo number_format($d['paid_amount'], 2); ?></td>
                            <td><strong class="text-danger">GH₵ <?php echo number_format($d['balance'], 2); ?></strong></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-bell"></i> Remind</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
