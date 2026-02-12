<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-heart-fill me-2 text-danger"></i> Alumni Donations</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Alumni</th>
                        <th>Student ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th class="pe-4">Received By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donations)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="<?= BASE_URL ?>assets/img/empty-payments.svg" alt="Empty" style="width: 100px; opacity: 0.3;" class="mb-3 d-block mx-auto">
                                <p class="text-muted">No donation records found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= $donation['first_name'] . ' ' . $donation['last_name'] ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $donation['student_id'] ?></span></td>
                                <td><div class="fw-bold text-success">$<?= number_format($donation['amount'], 2) ?></div></td>
                                <td><?= date('d M, Y', strtotime($donation['donation_date'])) ?></td>
                                <td><?= $donation['purpose'] ?></td>
                                <td class="pe-4 small text-muted"><?= $donation['received_by_name'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
