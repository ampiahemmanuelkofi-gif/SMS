<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-shield-fill-exclamation text-warning"></i> Dietary Restriction Registry</h2>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addRestrictionModal">
            <i class="bi bi-plus-lg me-1"></i> Add Entry
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm border-start border-4 border-warning">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th>Student Name</th>
                                <th>Category</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($restrictions as $r): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($r['full_name']); ?></strong></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo ucfirst($r['restriction_type']); ?></span></td>
                                    <td><span class="text-danger fw-bold"><?php echo Security::clean($r['details']); ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($restrictions)): ?>
                                <tr><td colspan="4" class="text-center py-5">No dietary restrictions recorded in the registry.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Restriction Modal -->
<div class="modal fade" id="addRestrictionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">New Dietary Alert</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>cafeteria/restrictions" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student</label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">Start typing name...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="restriction_type" class="form-select" required>
                            <option value="allergy">Allergy (Critical)</option>
                            <option value="religious">Religious (e.g. No Pork)</option>
                            <option value="medical">Medical / Diabetic</option>
                            <option value="other">Other Preference</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Specific Details</label>
                        <textarea name="details" class="form-control" rows="2" placeholder="e.g., Severe Peanut Allergy" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Add to Registry</button>
                </div>
            </form>
        </div>
    </div>
</div>
