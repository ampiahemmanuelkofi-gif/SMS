<div class="card-mobile p-3 bg-white mb-3">
    <label class="form-label small fw-bold">Select Section</label>
    <form action="<?php echo BASE_URL; ?>iteacher/attendance" method="GET">
        <select name="section_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">-- Select Class --</option>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>" <?php echo $sectionId == $section['id'] ? 'selected' : ''; ?>>
                    <?php echo $section['name']; ?> (<?php echo $section['class_name']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if (empty($students)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-person-slash fs-1"></i>
        <p class="mt-2">Select a class to mark attendance.</p>
    </div>
<?php else: ?>
    <form action="<?php echo BASE_URL; ?>attendance/save" method="POST">
        <input type="hidden" name="source" value="mobile">
        <input type="hidden" name="section_id" value="<?php echo $sectionId; ?>">
        <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
        
        <div class="card-mobile overflow-hidden bg-white">
            <div class="list-group list-group-flush">
                <?php foreach ($students as $student): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-mobile small me-3 bg-light">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></div>
                                <small class="text-muted"><?php echo $student['student_id']; ?></small>
                            </div>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="status[<?php echo $student['id']; ?>]" id="p_<?php echo $student['id']; ?>" value="present" checked>
                            <label class="btn btn-outline-success" for="p_<?php echo $student['id']; ?>">P</label>
                            
                            <input type="radio" class="btn-check" name="status[<?php echo $student['id']; ?>]" id="a_<?php echo $student['id']; ?>" value="absent">
                            <label class="btn btn-outline-danger" for="a_<?php echo $student['id']; ?>">A</label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-cloud-upload"></i> Submit Attendance
            </button>
        </div>
    </form>
<?php endif; ?>
