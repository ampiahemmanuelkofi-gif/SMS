<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-cash-stack"></i> Library Fines</h2>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-danger text-white">
        <h5 class="card-title mb-0">Outstanding Fines</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Borrower</th>
                    <th>Book Title</th>
                    <th>Due Date</th>
                    <th>Returned On</th>
                    <th>Fine Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fines as $fine): ?>
                    <tr>
                        <td><strong><?php echo Security::clean($fine['borrower_name']); ?></strong></td>
                        <td><?php echo Security::clean($fine['title']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($fine['due_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($fine['return_date'])); ?></td>
                        <td class="text-danger fw-bold">GH₵ <?php echo number_format($fine['amount'], 2); ?></td>
                        <td>
                            <span class="badge bg-warning text-dark"><?php echo ucfirst($fine['status']); ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-success">Collect Payment</button>
                            <button class="btn btn-sm btn-outline-secondary">Waive</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($fines)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No outstanding fines found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
