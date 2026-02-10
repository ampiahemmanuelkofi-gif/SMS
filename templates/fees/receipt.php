<?php
/**
 * Fee Receipt Printable Template
 */
?>
<div class="d-print-none mb-4 d-flex justify-content-between">
    <h2 class="page-title"><i class="bi bi-receipt"></i> Payment Receipt</h2>
    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Print Receipt</button>
</div>

<div class="printable-area p-5 bg-white border" style="max-width: 800px; margin: 0 auto;">
    <div class="row border-bottom pb-4 mb-4">
        <div class="col-8">
            <h4><?php echo strtoupper(SCHOOL_NAME); ?></h4>
            <p class="mb-0"><?php echo SCHOOL_ADDRESS; ?></p>
            <p class="mb-0">Tel: <?php echo SCHOOL_PHONE; ?></p>
        </div>
        <div class="col-4 text-end">
            <h2 class="text-muted">RECEIPT</h2>
            <p class="mb-0">Date: <?php echo date('d M, Y', strtotime($payment['payment_date'])); ?></p>
            <p>No: <strong>#REC-<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?></strong></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted text-uppercase small">Received From:</h6>
            <h5><?php echo Security::clean($payment['student_name']); ?></h5>
            <p class="mb-0">ID: <?php echo Security::clean($payment['student_id']); ?></p>
            <p>Class: <?php echo Security::clean($payment['class_name']); ?></p>
        </div>
        <div class="col-6 text-end">
            <h6 class="text-muted text-uppercase small">Payment For:</h6>
            <p class="mb-0"><?php echo Security::clean($payment['term_name']); ?></p>
            <p><?php echo Security::clean($payment['year_name']); ?></p>
        </div>
    </div>

    <table class="table table-bordered mb-4">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th class="text-end" width="150">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo Security::clean($payment['notes'] ?: 'School Fee Payment'); ?></td>
                <td class="text-end">GH₵ <?php echo number_format($payment['amount'], 2); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end text-uppercase">Total Paid:</th>
                <th class="text-end">GH₵ <?php echo number_format($payment['amount'], 2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="row mb-5">
        <div class="col-12">
            <div class="border p-2 rounded bg-light">
                <strong>Payment Method:</strong> <?php echo ucfirst($payment['payment_method']); ?> 
                <?php if($payment['reference_number']): ?>
                    | <strong>Ref:</strong> <?php echo Security::clean($payment['reference_number']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-6 border-top pt-2">
            <p class="small text-muted mb-0">Received By:</p>
            <p><?php echo Security::clean($payment['received_by_name']); ?></p>
        </div>
        <div class="col-6 text-end">
            <p class="small text-muted pt-5">Official Stamp/Signature</p>
        </div>
    </div>
    
    <div class="text-center mt-5 text-muted small">
        <p>Thank you for choosing <?php echo SCHOOL_NAME; ?>. This is a computer generated receipt.</p>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .printable-area, .printable-area * { visibility: visible; }
    .printable-area { position: absolute; left: 0; top: 0; width: 100%; border: none !important; }
    .d-print-none { display: none !important; }
}
</style>
