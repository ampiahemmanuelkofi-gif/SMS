<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-folder2-open"></i> Medical Folders</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="mb-0">Search Student Health Folder</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>health/records" method="GET">
                    <div class="mb-3">
                        <select name="user_id" class="form-select select2" onchange="this.form.submit()">
                            <option value="">Select a student...</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?php echo $u['id']; ?>" <?php echo (isset($_GET['user_id']) && $_GET['user_id'] == $u['id']) ? 'selected' : ''; ?>>
                                    <?php echo Security::clean($u['full_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($current_record): ?>
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="bg-primary bg-opacity-10 p-4 rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person-circle text-primary fs-1"></i>
                </div>
                <h4 class="fw-bold mb-1"><?php echo Security::clean($current_record['full_name']); ?></h4>
                <p class="text-muted small text-uppercase mb-3"><?php echo $current_record['role']; ?></p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i> Print Folder</button>
                    <button class="btn btn-outline-info btn-sm"><i class="bi bi-clock-history me-1"></i> View All History</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-8">
        <?php if ($current_record): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Personal Health Information</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>health/records" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $current_record['user_id']; ?>">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                                        <option value="<?php echo $bg; ?>" <?php echo $current_record['blood_group'] == $bg ? 'selected' : ''; ?>><?php echo $bg; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Emergency Phone</label>
                                <input type="text" name="emergency_phone" class="form-control" value="<?php echo Security::clean($current_record['emergency_contact_phone']); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-danger"><i class="bi bi-shield-exclamation me-1"></i> Allergies</label>
                                <textarea name="allergies" class="form-control border-danger-subtle" rows="2" placeholder="List any medicine, food, or skin allergies..."><?php echo Security::clean($current_record['allergies']); ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Chronic Conditions / Ongoing Medication</label>
                                <textarea name="chronic_conditions" class="form-control" rows="2" placeholder="e.g. Asthma, Diabetes..."><?php echo Security::clean($current_record['chronic_conditions']); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Emergency Contact Name</label>
                                <input type="text" name="emergency_name" class="form-control" value="<?php echo Security::clean($current_record['emergency_contact_name']); ?>">
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Update Medical Record</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm bg-light d-flex align-items-center justify-content-center p-5 text-center" style="min-height: 400px;">
                <div class="p-5">
                    <i class="bi bi-search fs-1 text-muted mb-4"></i>
                    <h5 class="text-muted">Please select a student from the sidebar to view or edit their medical records.</h5>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
