<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title text-danger"><i class="bi bi-shield-lock"></i> GDPR & Data Protection Tools</h2>
        <a href="<?php echo BASE_URL; ?>safeguarding" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Hub
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="table-card p-4">
            <h5 class="mb-3"><i class="bi bi-person-x-fill text-danger"></i> Right to be Forgotten</h5>
            <p class="text-muted small">Anonymize all safeguarding records for a specific student. This process is irreversible and will replace sensitive descriptive text with [ANONYMIZED] data tags.</p>
            <form action="<?php echo BASE_URL; ?>safeguarding/anonymize" method="POST" onsubmit="return confirm('WARNING: This will permanently anonymize ALL welfare data for this student. Are you sure?');">
                <div class="mb-3">
                    <label class="form-label">Enter Student ID</label>
                    <input type="text" name="student_code" class="form-control" placeholder="e.g., SCH-2024-0001" required>
                </div>
                <button type="submit" class="btn btn-outline-danger">Execute Anonymization</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="table-card p-4">
            <h5 class="mb-3"><i class="bi bi-archive-fill text-primary"></i> Data Retention Enforcement</h5>
            <p class="text-muted small">Manage the lifecycle of safeguarding data. Current policy: Retain records for **25 years** after student leaves (standard educational practice).</p>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <h6 class="mb-0">Auto-Archive Closed Cases</h6>
                        <small class="text-muted">Archive cases closed for more than 5 years.</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <h6 class="mb-0">Permanent Purge Entry</h6>
                        <small class="text-muted">Delete records for students older than 25 years.</small>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary">Run Cleanup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-card p-4">
            <h5 class="mb-3"><i class="bi bi-list-check"></i> Access & Audit Logs</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover small">
                    <thead class="bg-light">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Target Case</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            <td>System Administrator</td>
                            <td><span class="badge bg-info">view</span></td>
                            <td>Hub Overview</td>
                            <td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo date('Y-m-d H:i:s', strtotime('-5 mins')); ?></td>
                            <td>System Administrator</td>
                            <td><span class="badge bg-danger">view</span></td>
                            <td>Case SCH-2024-0003</td>
                            <td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
