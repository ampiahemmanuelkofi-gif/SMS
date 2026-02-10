<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title text-danger"><i class="bi bi-shield-lock-fill"></i> Safeguarding Hub</h2>
        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>safeguarding/add" class="btn btn-danger">
                <i class="bi bi-plus-lg"></i> Record New Concern
            </a>
            <?php if (Auth::hasRole('super_admin')): ?>
                <a href="<?php echo BASE_URL; ?>safeguarding/gdpr" class="btn btn-outline-secondary">
                    <i class="bi bi-shield-check"></i> GDPR Tools
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Filters -->
    <div class="col-md-12">
        <div class="table-card p-3">
            <form action="<?php echo BASE_URL; ?>safeguarding" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="open" <?php echo $filters['status'] == 'open' ? 'selected' : ''; ?>>Open Cases</option>
                        <option value="in_progress" <?php echo $filters['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="referred" <?php echo $filters['status'] == 'referred' ? 'selected' : ''; ?>>Referred</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Severity</label>
                    <select name="severity" class="form-select form-select-sm">
                        <option value="">All Severities</option>
                        <option value="low" <?php echo $filters['severity'] == 'low' ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo $filters['severity'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo $filters['severity'] == 'high' ? 'selected' : ''; ?>>High</option>
                        <option value="critical" <?php echo $filters['severity'] == 'critical' ? 'selected' : ''; ?>>Critical</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card p-4 border-top border-4 border-danger">
            <h5 class="mb-4">Recent Welfare Concerns</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Incident Date</th>
                            <th>Concern</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($concerns)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No active concerns found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($concerns as $concern): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $concern['first_name'] . ' ' . $concern['last_name']; ?></strong><br>
                                        <small class="text-muted"><?php echo $concern['student_code']; ?></small>
                                    </td>
                                    <td><?php echo date('d M, Y', strtotime($concern['incident_date'])); ?></td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            <?php echo Security::clean($concern['title']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            $sevClass = [
                                                'low' => 'info',
                                                'medium' => 'warning',
                                                'high' => 'danger',
                                                'critical' => 'dark'
                                            ][$concern['severity']];
                                        ?>
                                        <span class="badge bg-<?php echo $sevClass; ?>"><?php echo ucfirst($concern['severity']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-outline-<?php echo $concern['status'] == 'open' ? 'danger' : 'secondary'; ?> border">
                                            <?php echo str_replace('_', ' ', ucfirst($concern['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $concern['recorder_name']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>safeguarding/case_details/<?php echo $concern['id']; ?>" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-eye-fill"></i> View Chronology
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-warning mt-4 shadow-sm border-start border-4 border-warning">
    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Data Sensitivity Notice</h6>
    <p class="mb-0 small">Access to this hub is strictly audited. Every view, edit, or report generated is recorded with your user ID and IP address to ensure GDPR and child protection compliance.</p>
</div>
