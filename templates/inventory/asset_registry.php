<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-laptop"></i> Fixed Asset Registry</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal">
            <i class="bi bi-plus-lg me-1"></i> Register New Asset
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search by name or serial...">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Asset Name</th>
                            <th>Category / Brand</th>
                            <th>Serial Number</th>
                            <th>Location / Assignee</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assets as $a): ?>
                            <tr>
                                <td>
                                    <strong><?php echo Security::clean($a['asset_name']); ?></strong>
                                    <br><small class="text-muted">Pur. Date: <?php echo $a['purchase_date']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info"><?php echo Security::clean($a['category_name']); ?></span>
                                    <br><small><?php echo Security::clean($a['brand']); ?></small>
                                </td>
                                <td><code><?php echo Security::clean($a['serial_number']); ?></code></td>
                                <td>
                                    <i class="bi bi-geo-alt me-1 text-muted"></i> <?php echo Security::clean($a['location']); ?><br>
                                    <i class="bi bi-person me-1 text-muted"></i> <small><?php echo Security::clean($a['assignee'] ?? 'Unassigned'); ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $class = [
                                        'active' => 'bg-success',
                                        'under_repair' => 'bg-warning text-dark',
                                        'disposed' => 'bg-secondary',
                                        'lost' => 'bg-danger'
                                    ][$a['status']] ?? 'bg-light text-dark';
                                    ?>
                                    <span class="badge <?php echo $class; ?>"><?php echo ucfirst(str_replace('_', ' ', $a['status'])); ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-light text-info"><i class="bi bi-tools"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Register New Fixed Asset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>inventory/assets" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Asset Name</label>
                            <input type="text" name="asset_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Brand / Manufacturer</label>
                            <input type="text" name="brand" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Cost (GHS)</label>
                            <input type="number" step="0.01" name="purchase_cost" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Lab 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Assign to Staff Member</label>
                            <select name="assigned_to" class="form-select select2">
                                <option value="">Optional: Choose staff...</option>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Register Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
