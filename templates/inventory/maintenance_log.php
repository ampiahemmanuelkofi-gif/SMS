<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-tools"></i> Maintenance & Repair Logs</h2>
        <button class="btn btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#logMaintenanceModal">
            <i class="bi bi-plus-lg me-1"></i> Log Maintenance Event
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm border-start border-4 border-info">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Asset Name</th>
                            <th>Type / Summary</th>
                            <th>Cost (GHS)</th>
                            <th>Conducted By</th>
                            <th>Next Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($l['maintenance_date'])); ?></td>
                                <td><strong><?php echo Security::clean($l['asset_name']); ?></strong></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info text-uppercase small"><?php echo $l['maintenance_type']; ?></span>
                                    <br><small class="text-muted"><?php echo Security::clean($l['description']); ?></small>
                                </td>
                                <td class="fw-bold">GHS <?php echo number_format($l['cost'], 2); ?></td>
                                <td><?php echo Security::clean($l['conducted_by']); ?></td>
                                <td>
                                    <?php if ($l['next_due_date']): ?>
                                        <small class="text-primary fw-bold"><i class="bi bi-calendar-event me-1"></i> <?php echo date('M d, Y', strtotime($l['next_due_date'])); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="6" class="text-center py-5">No maintenance records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Log Maintenance Modal -->
<div class="modal fade" id="logMaintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Log Asset Maintenance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>inventory/maintenance" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Asset</label>
                        <select name="asset_id" class="form-select select2" required>
                            <option value="">Choose asset...</option>
                            <?php foreach ($assets as $a): ?>
                                <option value="<?php echo $a['id']; ?>"><?php echo Security::clean($a['asset_name']); ?> (<?php echo $a['serial_number']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" name="maintenance_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type</label>
                            <select name="maintenance_type" class="form-select" required>
                                <option value="routine">Routine Check</option>
                                <option value="repair">Major Repair</option>
                                <option value="upgrade">Software/HW Upgrade</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description of Work</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cost (GHS)</label>
                            <input type="number" step="0.01" name="cost" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Conducted By</label>
                            <input type="text" name="conducted_by" class="form-control" placeholder="Vendor or Staff name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info px-4 fw-bold">Save Log Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>
