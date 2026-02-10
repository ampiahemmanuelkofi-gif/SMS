<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-cash-stack"></i> Fee Structure</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">
            <i class="bi bi-plus-circle"></i> Add Fee Set
        </button>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Academic Period</th>
                    <th>Class</th>
                    <th>Category</th>
                    <th>Fee Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($structures as $s): ?>
                    <tr>
                        <td><?php echo Security::clean($s['year_name'] . ' - ' . $s['term_name']); ?></td>
                        <td><?php echo Security::clean($s['class_name']); ?></td>
                        <td><span class="badge bg-info"><?php echo Security::clean($s['category_name'] ?? 'Other'); ?></span></td>
                        <td><?php echo Security::clean($s['description']); ?></td>
                        <td><strong>GH₵ <?php echo number_format($s['amount'], 2); ?></strong></td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Fee Modal -->
<div class="modal fade" id="addFeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>fees/addStructure" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header"><h5>Add Fee Structure</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Term</label>
                        <select name="term_id" class="form-select" required>
                            <?php foreach ($terms as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo $t['year_name'] . ' - ' . $t['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Fee Name (e.g. Tuition Fee)</label><input type="text" name="description" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Amount (GH₵)</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Fee</button></div>
            </form>
        </div>
    </div>
</div>
