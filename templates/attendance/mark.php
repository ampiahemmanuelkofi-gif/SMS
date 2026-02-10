<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-calendar-check-fill"></i> Mark Attendance</h2>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>attendance/mark" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" onchange="loadSections(this.value)" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>" <?php echo $filters['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($class['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Section</label>
            <select name="section_id" id="section_id" class="form-select" required>
                <option value="">Select Section</option>
                <!-- AJAX populated -->
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo $filters['date']; ?>" max="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Load Class List
            </button>
        </div>
    </form>
</div>

<?php if (!empty($students)): ?>
    <div class="table-card">
        <form action="<?php echo BASE_URL; ?>attendance/save" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="section_id" value="<?php echo $filters['section_id']; ?>">
            <input type="hidden" name="class_id" value="<?php echo $filters['class_id']; ?>">
            <input type="hidden" name="date" value="<?php echo $filters['date']; ?>">
            
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h5>Marking attendance for: <?php echo date('D, M d, Y', strtotime($filters['date'])); ?></h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('present')">All Present</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('absent')">All Absent</button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="100">ID</th>
                            <th>Student Name</th>
                            <th width="350" class="text-center">Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <?php $status = $existingRecords[$student['id']] ?? 'present'; ?>
                            <tr>
                                <td><?php echo Security::clean($student['student_id']); ?></td>
                                <td><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input att-radio present" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="present" id="p<?php echo $student['id']; ?>" <?php echo $status === 'present' ? 'checked' : ''; ?>>
                                            <label class="form-check-label text-success" for="p<?php echo $student['id']; ?>">Present</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input att-radio absent" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="absent" id="a<?php echo $student['id']; ?>" <?php echo $status === 'absent' ? 'checked' : ''; ?>>
                                            <label class="form-check-label text-danger" for="a<?php echo $student['id']; ?>">Absent</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input att-radio late" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="late" id="l<?php echo $student['id']; ?>" <?php echo $status === 'late' ? 'checked' : ''; ?>>
                                            <label class="form-check-label text-warning" for="l<?php echo $student['id']; ?>">Late</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="remarks[<?php echo $student['id']; ?>]" class="form-control form-control-sm" placeholder="Optional remark">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-5 py-2">
                    <i class="bi bi-save"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
<?php elseif($filters['section_id']): ?>
    <div class="alert alert-info py-4 text-center">
        <i class="bi bi-info-circle fs-3 mb-2 d-block"></i>
        No active students found in the selected section.
    </div>
<?php endif; ?>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    const currentSection = '<?php echo $filters['section_id']; ?>';
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                if (section.id == currentSection) option.selected = true;
                sectionSelect.appendChild(option);
            });
        });
}

function markAll(status) {
    document.querySelectorAll('.att-radio.' + status).forEach(radio => {
        radio.checked = true;
    });
}

// Initial load if class is selected
<?php if ($filters['class_id']): ?>
window.onload = function() { loadSections(<?php echo $filters['class_id']; ?>); };
<?php endif; ?>
</script>
