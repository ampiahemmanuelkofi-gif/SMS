<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-arrow-left-right"></i> Circulation Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Issue Book</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>library/circulation" method="POST">
                    <input type="hidden" name="action" value="issue">
                    <div class="mb-3">
                        <label class="form-label">Book Barcode / ISBN</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" name="barcode" class="form-control" placeholder="Scan or enter barcode" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borrower (Student/Staff)</label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">Select Borrower</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>">
                                    <?php echo Security::clean($student['full_name']); ?> (<?php echo $student['username']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Process Issue</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Active Loans</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Book / Barcode</th>
                            <th>Borrower</th>
                            <th>Issued On</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeLoans as $loan): ?>
                            <tr>
                                <td>
                                    <strong><?php echo Security::clean($loan['title']); ?></strong><br>
                                    <small class="text-muted"><?php echo $loan['barcode']; ?></small>
                                </td>
                                <td><?php echo Security::clean($loan['borrower_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($loan['issue_date'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($loan['due_date'])); ?></td>
                                <td>
                                    <?php 
                                    $isOverdue = strtotime($loan['due_date']) < time();
                                    ?>
                                    <span class="badge <?php echo $isOverdue ? 'bg-danger' : 'bg-primary'; ?>">
                                        <?php echo $isOverdue ? 'OVERDUE' : 'Issued'; ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="<?php echo BASE_URL; ?>library/circulation" method="POST" onsubmit="return confirm('Confirm book return?')">
                                        <input type="hidden" name="action" value="return">
                                        <input type="hidden" name="circulation_id" value="<?php echo $loan['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Return</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($activeLoans)): ?>
                            <tr><td colspan="6" class="text-center py-4">No active loans found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
