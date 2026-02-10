<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title text-danger"><i class="bi bi-clipboard-pulse"></i> Case Management</h2>
        <a href="<?php echo BASE_URL; ?>safeguarding" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Hub
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Case Overview -->
    <div class="col-md-4">
        <div class="table-card p-4 h-100 border-start border-4 border-danger">
            <h5 class="border-bottom pb-2 mb-3">Student Profile</h5>
            <div class="d-flex align-items-center mb-4">
                <div class="avatar-lg bg-soft-danger text-danger rounded-circle me-3 p-3">
                    <i class="bi bi-person-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="mb-0"><?php echo $concern['first_name'] . ' ' . $concern['last_name']; ?></h5>
                    <span class="text-muted"><?php echo $concern['student_code']; ?></span>
                </div>
            </div>
            
            <h6 class="fw-bold mb-1">Current Case Status</h6>
            <p><span class="badge bg-outline-danger border"><?php echo ucfirst($concern['status']); ?></span></p>
            
            <h6 class="fw-bold mb-1">Risk Level</h6>
            <p><span class="badge bg-danger"><?php echo ucfirst($concern['severity']); ?> Risk</span></p>
            
            <hr>
            
            <h6 class="fw-bold small text-muted text-uppercase">Recorded By</h6>
            <p class="mb-3"><?php echo $concern['recorder_name']; ?></p>
            
            <h6 class="fw-bold small text-muted text-uppercase">Incident Date</h6>
            <p class="mb-3"><?php echo date('M d, Y', strtotime($concern['incident_date'])); ?></p>
            
            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addActionModal">
                    <i class="bi bi-plus-circle"></i> Add Action Entry
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer"></i> Print Full Chronology
                </button>
            </div>
        </div>
    </div>

    <!-- Case Details & Chronology -->
    <div class="col-md-8">
        <div class="table-card p-4 mb-4">
            <h5 class="mb-3 border-bottom pb-2">Concern Detail: <?php echo Security::clean($concern['title']); ?></h5>
            <div class="bg-light p-3 rounded mb-4">
                <p class="mb-0"><?php echo nl2br(Security::clean($concern['description'])); ?></p>
            </div>
            
            <h5 class="mb-3 border-bottom pb-2">Student Chronology (Timeline)</h5>
            <div class="timeline-v2">
                <?php if (empty($chronology)): ?>
                    <p class="text-center text-muted py-4">No chronology entries yet.</p>
                <?php else: ?>
                    <?php foreach ($chronology as $event): ?>
                        <div class="timeline-item d-flex mb-4">
                            <div class="timeline-date pe-3 border-end text-end" style="min-width: 120px;">
                                <div class="fw-bold"><?php echo date('d M, Y', strtotime($event['event_date'])); ?></div>
                                <small class="text-muted"><?php echo date('H:i', strtotime($event['event_date'])); ?></small>
                            </div>
                            <div class="timeline-content ps-4">
                                <div class="badge bg-<?php echo $event['event_type'] == 'concern' ? 'danger' : 'info'; ?> mb-1">
                                    <?php echo ucfirst($event['event_type']); ?>
                                </div>
                                <h6 class="fw-bold"><?php echo Security::clean($event['title']); ?></h6>
                                <p class="small text-muted mb-0"><?php echo nl2br(Security::clean($event['description'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Action Modal -->
<div class="modal fade" id="addActionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>safeguarding/add_action" method="POST" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Record Action / Update</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="concern_id" value="<?php echo $concern['id']; ?>">
                <div class="mb-3">
                    <label class="form-label">Action Type</label>
                    <select name="action_type" class="form-select" required>
                        <option value="Internal Meeting">Internal Meeting</option>
                        <option value="Parent Meeting">Parent Meeting</option>
                        <option value="Phone Call">Phone Call / Email</option>
                        <option value="Student Session">Student Session</option>
                        <option value="Multi-agency Referral">Multi-agency Referral</option>
                        <option value="Social Services Contact">Social Services Contact</option>
                        <option value="Police Contact">Police Contact</option>
                        <option value="Case Update">Other Case Update</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Action Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Detail what happened, what was said, and next steps." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Add to Chronology</button>
            </div>
        </form>
    </div>
</div>

<style>
.timeline-item { position: relative; }
.timeline-item::before {
    content: '';
    position: absolute;
    left: 120px;
    top: 5px;
    height: 100%;
    width: 2px;
    background: #e9ecef;
}
.timeline-content::before {
    content: '';
    position: absolute;
    left: 114px;
    top: 8px;
    width: 14px;
    height: 14px;
    background: #dc3545;
    border-radius: 50%;
    border: 3px solid #fff;
    z-index: 1;
}
</style>
