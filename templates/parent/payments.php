<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-wallet2"></i> Payment History</h2>
        <p class="text-muted">History of fee payments made for your children.</p>
    </div>
</div>

<div class="table-card shadow-sm border-0">
    <?php if (empty($payments)): ?>
        <p class="text-center py-4 text-muted">No payment records found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Term</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($p['payment_date'])); ?></td>
                            <td><?php echo Security::clean($p['first_name'] . ' ' . $p['last_name']); ?></td>
                            <td><?php echo Security::clean($p['term_name']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo Security::clean($p['payment_method']); ?></span></td>
                            <td class="fw-bold">GH₵ <?php echo number_format($p['amount'], 2); ?></td>
                            <td class="text-end">
                                <a href="<?php echo BASE_URL; ?>fees/receipt/<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-printer"></i> Receipt
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
