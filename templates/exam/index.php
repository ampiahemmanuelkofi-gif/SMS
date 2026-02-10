<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-journal-check"></i> Examination Sessions</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addExamModal">
            <i class="bi bi-plus-lg me-1"></i> Setup New Exam
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-info">
                        <tr>
                            <th>Exam Info</th>
                            <th>Subject</th>
                            <th>Term / Year</th>
                            <th>Max Marks</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exams as $e): ?>
                            <tr>
                                <td>
                                    <strong><?php echo Security::clean($e['type_name']); ?></strong><br>
                                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> <?php echo $e['exam_date']; ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo Security::clean($e['subject_name']); ?></span></td>
                                <td>
                                    <small><?php echo Security::clean($e['term_name']); ?></small><br>
                                    <small class="text-muted"><?php echo Security::clean($e['year_name']); ?></small>
                                </td>
                                <td><span class="fw-bold"><?php echo $e['max_marks']; ?></span></td>
                                <td><span class="badge bg-success">Ongoing</span></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>exam/marks/<?php echo $e['id']; ?>" class="btn btn-sm btn-dark">
                                        <i class="bi bi-pencil-square me-1"></i> Enter Marks
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($exams)): ?>
                            <tr><td colspan="6" class="text-center py-5">No examination sessions created yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Exam Modal -->
<div class="modal fade" id="addExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Create Exam Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>exam/addExam" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Exam Type</label>
                        <select name="exam_type_id" class="form-select" required>
                            <?php foreach ($exam_types as $et): ?>
                                <option value="<?php echo $et['id']; ?>"><?php echo $et['name']; ?> (<?php echo $et['contribution_percentage']; ?>%)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" class="form-select select2" required>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['name']); ?> (<?php echo $s['code']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Academic Term</label>
                        <select name="term_id" class="form-select" required>
                            <?php foreach ($terms as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo $t['is_current'] ? 'selected' : ''; ?>>
                                    <?php echo $t['name']; ?> - <?php echo $t['year_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Exam Date</label>
                            <input type="date" name="exam_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Max Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Create Session</button>
                </div>
            </form>
        </div>
    </div>
</div>
