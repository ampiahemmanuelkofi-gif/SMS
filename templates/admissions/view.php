<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admissions">Admissions</a></li>
                <li class="breadcrumb-item active">Application #<?php echo Security::clean($application['application_number']); ?></li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-title">
                <?php echo Security::clean($application['first_name'] . ' ' . $application['last_name']); ?>
                <?php 
                    $statusClass = 'secondary';
                    if ($application['status'] === 'accepted') $statusClass = 'success';
                    if ($application['status'] === 'pending') $statusClass = 'warning';
                    if ($application['status'] === 'rejected') $statusClass = 'danger';
                    if ($application['status'] === 'interview_scheduled') $statusClass = 'info';
                ?>
                <span class="badge bg-<?php echo $statusClass; ?> fs-6 align-middle ms-2">
                    <?php echo ucwords(str_replace('_', ' ', $application['status'])); ?>
                </span>
            </h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Applicant Details Card -->
        <div class="table-card mb-4">
            <h5 class="border-bottom pb-2 mb-3">Applicant Details</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small">Full Name</label>
                    <p class="fw-bold"><?php echo Security::clean($application['first_name'] . ' ' . $application['last_name']); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="text-muted small">Application Number</label>
                    <p class="fw-bold"><?php echo Security::clean($application['application_number']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">Gender</label>
                    <p class="fw-bold"><?php echo ucfirst($application['gender']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">Date of Birth</label>
                    <p class="fw-bold"><?php echo date('M d, Y', strtotime($application['date_of_birth'])); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="text-muted small">Applying For</label>
                    <p class="fw-bold"><?php echo Security::clean($application['class_name']); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="text-muted small">Application Date</label>
                    <p class="fw-bold"><?php echo date('M d, Y', strtotime($application['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Guardian Details Card -->
        <div class="table-card mb-4">
            <h5 class="border-bottom pb-2 mb-3">Guardian Information</h5>
             <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small">Guardian Name</label>
                    <p class="fw-bold"><?php echo Security::clean($application['guardian_name']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">Phone</label>
                    <p class="fw-bold"><?php echo Security::clean($application['guardian_phone']); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="text-muted small">Email</label>
                    <p class="fw-bold"><?php echo Security::clean($application['guardian_email'] ?: 'N/A'); ?></p>
                </div>
                 <div class="col-md-12">
                    <label class="text-muted small">Address</label>
                    <p class="fw-bold"><?php echo nl2br(Security::clean($application['address'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Management Card -->
        <div class="table-card mb-4">
            <h5 class="border-bottom pb-2 mb-3">Application Action</h5>
            <div class="d-grid gap-2 mb-3">
                <?php if ($application['status'] === 'pending' || $application['status'] === 'interview_scheduled'): ?>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                        <i class="bi bi-calendar-event"></i> Schedule Interview
                    </button>
                <?php endif; ?>
                
                <?php if ($application['status'] === 'accepted' || $application['status'] === 'offered'): ?>
                    <a href="<?php echo BASE_URL; ?>admissions/generate_offer/<?php echo $application['id']; ?>" target="_blank" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-check"></i> Generate Offer Letter
                    </a>
                <?php endif; ?>
            </div>

            <form action="<?php echo BASE_URL; ?>admissions/update_status" method="POST">
                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-select">
                        <?php 
                        $statuses = ['pending', 'interview_scheduled', 'tested', 'offered', 'accepted', 'rejected', 'waitlisted', 'enrolled'];
                        foreach ($statuses as $status): 
                        ?>
                            <option value="<?php echo $status; ?>" <?php echo $application['status'] === $status ? 'selected' : ''; ?>>
                                <?php echo ucwords(str_replace('_', ' ', $status)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Status</button>
            </form>
        </div>
        
        <!-- Documents Quick View -->
        <div class="table-card">
             <h5 class="border-bottom pb-2 mb-3">Submitted Documents</h5>
             <?php if(empty($documents)): ?>
                <p class="text-muted small text-center py-2">No documents uploaded.</p>
             <?php else: ?>
                 <ul class="list-group list-group-flush">
                    <?php foreach($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <?php echo Security::clean($doc['document_name']); ?>
                            </div>
                            <a href="<?php echo BASE_URL . $doc['file_path']; ?>" target="_blank" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                        </li>
                    <?php endforeach; ?>
                 </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admissions/schedule_interview" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="admission_id" value="<?php echo $application['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label required">Interview Date & Time</label>
                        <input type="datetime-local" name="interview_date" class="form-control" required>
                    </div>
                    
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i> This will update the application status to "Interview Scheduled".
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
