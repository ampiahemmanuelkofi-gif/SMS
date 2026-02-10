<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-journal-medical"></i> Clinic Visit Log</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#newVisitModal">
            <i class="bi bi-plus-lg me-1"></i> Log Patient Visit
        </button>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Patient Name</th>
                                <th>Vitals</th>
                                <th>Symptoms/Diagnosis</th>
                                <th>Treatment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visits as $v): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo date('M d, Y', strtotime($v['visit_date'])); ?></div>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($v['visit_date'])); ?></small>
                                    </td>
                                    <td><strong><?php echo Security::clean($v['patient_name']); ?></strong></td>
                                    <td>
                                        <div class="small">
                                            Temp: <span class="text-danger fw-bold"><?php echo $v['temperature']; ?>°C</span><br>
                                            BP: <?php echo $v['blood_pressure']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;" title="<?php echo Security::clean($v['symptoms']); ?>">
                                            <strong>S:</strong> <?php echo Security::clean($v['symptoms']); ?>
                                        </div>
                                        <div class="small text-truncate text-primary" style="max-width: 200px;" title="<?php echo Security::clean($v['diagnosis']); ?>">
                                            <strong>D:</strong> <?php echo Security::clean($v['diagnosis']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?php echo Security::clean($v['treatment']); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-light text-primary"><i class="bi bi-printer"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($visits)): ?>
                                <tr><td colspan="6" class="text-center py-5">No visits logged for this period.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Visit Modal -->
<div class="modal fade" id="newVisitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Log New Patient Visit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>health/visits" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select Patient (Student)</label>
                            <select name="user_id" class="form-select select2" required>
                                <option value="">Start typing name...</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?php echo $u['id']; ?>"><?php echo Security::clean($u['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Temp (°C)</label>
                            <input type="number" step="0.1" name="temperature" class="form-control" placeholder="36.5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">BP</label>
                            <input type="text" name="blood_pressure" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Symptoms</label>
                            <textarea name="symptoms" class="form-control" rows="3" required placeholder="Describe complaints..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Diagnosis</label>
                            <textarea name="diagnosis" class="form-control" rows="3" placeholder="Medical findings..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Treatment & Plan</label>
                            <textarea name="treatment" class="form-control" rows="2" placeholder="Prescription, rest, referral..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <hr class="my-2">
                            <h6 class="fw-bold text-primary mb-3">Medication (Optional)</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="medication" class="form-control" placeholder="Medicine Name">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="dosage" class="form-control" placeholder="Dosage (e.g. 500mg)">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="instructions" class="form-control" placeholder="Tablets x 3/day">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Save Visit Log</button>
                </div>
            </form>
        </div>
    </div>
</div>
