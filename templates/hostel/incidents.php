<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-shield-exclamation text-danger"></i> Discipline & Incident Tracking</h2>
        <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#logIncidentModal">
            <i class="bi bi-plus-circle me-1"></i> Log Incident
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Hostel Incident Registry</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Reported By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incidents as $i): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($i['incident_date'])); ?></td>
                                <td><strong><?php echo Security::clean($i['student_name']); ?></strong></td>
                                <td><span class="badge bg-danger-subtle text-danger"><?php echo ucfirst($i['category']); ?></span></td>
                                <td><small><?php echo Security::clean($i['description']); ?></small></td>
                                <td><small class="text-muted"><?php echo Security::clean($i['reporter_name']); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($incidents)): ?>
                            <tr><td colspan="5" class="text-center py-5">No incidents recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Log Incident Modal -->
<div class="modal fade" id="logIncidentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">New Incident Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>hostel/incidents" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-select select2" required>
                            <option value="">Search student...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="incident_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="curfew">Curfew Violation</option>
                            <option value="noise">Noise Complaint</option>
                            <option value="cleanliness">Poor Cleanliness</option>
                            <option value="theft">Theft</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Action Taken</label>
                        <textarea name="action_taken" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
