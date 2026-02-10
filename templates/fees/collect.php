<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-wallet2"></i> Collect Fees</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="table-card">
            <h5>Select Student</h5>
            <div class="mb-3">
                <label class="form-label">Search Student</label>
                <input type="text" id="studentSearch" class="form-control" placeholder="ID or Name..." onkeyup="searchStudents(this.value)">
                <div id="searchResults" class="list-group mt-2 shadow-sm" style="display:none; position:absolute; z-index:100; width:90%;"></div>
            </div>
            
            <?php if ($student): ?>
                <hr>
                <div class="text-center mb-3">
                    <div class="user-avatar mx-auto mb-2" style="width: 80px; height: 80px;">
                        <?php echo strtoupper(substr($student['first_name'], 0, 1)); ?>
                    </div>
                    <h6><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></h6>
                    <p class="text-muted small"><?php echo Security::clean($student['student_id']); ?> - <?php echo Security::clean($student['class_name']); ?></p>
                </div>
                <div class="alert alert-warning text-center">
                    <small class="d-block">Outstanding Balance</small>
                    <h4 class="mb-0">GH₵ <?php echo number_format($balance['balance'], 2); ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <?php if ($student): ?>
            <div class="table-card mb-4">
                <h5>Payment Details</h5>
                <form action="<?php echo BASE_URL; ?>fees/savePayment" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                    
                    <?php 
                    $db = getDbConnection();
                    $currentTerm = $db->query("SELECT id FROM terms WHERE is_current = 1 LIMIT 1")->fetch();
                    ?>
                    <input type="hidden" name="term_id" value="<?php echo $currentTerm['id'] ?? ''; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Amount Paid (GH₵)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference #</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="Receipt or MoMo ID">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. 1st term fees part payment"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success px-5">
                            <i class="bi bi-receipt"></i> Process & Generate Receipt
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="table-card py-5 text-center text-muted">
                <i class="bi bi-person-search fs-1 mb-3"></i>
                <p>Please search and select a student to record a payment</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function searchStudents(query) {
    if (query.length < 2) {
        document.getElementById('searchResults').style.display = 'none';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>students/search_ajax?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            const results = document.getElementById('searchResults');
            results.innerHTML = '';
            if (data.length > 0) {
                data.forEach(s => {
                    const item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action';
                    item.href = '<?php echo BASE_URL; ?>fees/collect/' + s.id;
                    item.innerHTML = `<strong>${s.first_name} ${s.last_name}</strong><br><small class="text-muted">${s.student_id} | ${s.class_name}</small>`;
                    results.appendChild(item);
                });
                results.style.display = 'block';
            } else {
                results.style.display = 'none';
            }
        });
}
</script>
