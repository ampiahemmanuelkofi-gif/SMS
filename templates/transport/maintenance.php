<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-tools"></i> Maintenance Logs</h2>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addLogModal">
            <i class="bi bi-plus-circle"></i> Log Service
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Vehicle</th>
                    <th>Service Type</th>
                    <th>Cost (GHS)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($log['service_date'])); ?></td>
                        <td><strong><?php echo Security::clean($log['plate_number']); ?></strong></td>
                        <td><?php echo Security::clean($log['service_type']); ?></td>
                        <td class="fw-bold">GHS <?php echo number_format($log['cost'], 2); ?></td>
                        <td><small><?php echo Security::clean($log['notes']); ?></small></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No maintenance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Log Modal -->
<div class="modal fade" id="addLogModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Maintenance Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>transport/maintenance" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-select" required>
                            <option value="">Select Vehicle</option>
                            <?php foreach ($vehicles as $v): ?>
                                <option value="<?php echo $v['id']; ?>">
                                    <?php echo Security::clean($v['plate_number']); ?> (<?php echo $v['vehicle_model']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Date</label>
                        <input type="date" name="service_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <input type="text" name="service_type" class="form-control" placeholder="e.g., Oil Change, Tire Replacement" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Cost (GHS)</label>
                        <input type="number" step="0.01" name="cost" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Log</button>
                </div>
            </form>
        </div>
    </div>
</div>
