<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-arrow-up-circle-fill"></i> Student Promotions</h2>
        <p class="text-muted">Move students from their current class to the next academic level.</p>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>promotions" method="GET" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">From Class</label>
            <select name="from_class_id" class="form-select" onchange="loadSections(this.value, 'from_section_id')" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $filters['from_class_id'] == $c['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">From Section</label>
            <select name="from_section_id" id="from_section_id" class="form-select" required>
                <option value="">Select Section</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Load Student List
            </button>
        </div>
    </form>
</div>

<?php if (!empty($students)): ?>
    <form action="<?php echo BASE_URL; ?>promotions/process" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
        <input type="hidden" name="from_section_id" value="<?php echo $filters['from_section_id']; ?>">
        
        <div class="row">
            <div class="col-md-8">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Selected Students</h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll()">Toggle All</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40"><input type="checkbox" id="checkAll" onclick="toggleAll()"></th>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $s): ?>
                                    <tr>
                                        <td><input type="checkbox" name="student_ids[]" value="<?php echo $s['id']; ?>" class="student-check"></td>
                                        <td><?php echo $s['student_id']; ?></td>
                                        <td><?php echo Security::clean($s['first_name'] . ' ' . $s['last_name']); ?></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="table-card">
                    <h5>Promotion Details</h5>
                    <div class="mb-3">
                        <label class="form-label">To Academic Year</label>
                        <select name="to_year_id" class="form-select" required>
                            <?php foreach ($years as $y): ?>
                                <option value="<?php echo $y['id']; ?>" <?php echo $y['is_current'] ? 'selected' : ''; ?>>
                                    <?php echo Security::clean($y['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To Class</label>
                        <select id="to_class_id" class="form-select" onchange="loadSections(this.value, 'to_section_id')" required>
                            <option value="">Select Target Class</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo Security::clean($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To Section</label>
                        <select name="to_section_id" id="to_section_id" class="form-select" required>
                            <option value="">Select Target Section</option>
                        </select>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold" onclick="return confirm('Ensure you have verified the target class. This action updates student records.')">
                        <i class="bi bi-check-circle"></i> Promote Selected
                    </button>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>

<script>
function loadSections(classId, targetSelectId) {
    const sectionSelect = document.getElementById(targetSelectId);
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                if (targetSelectId === 'from_section_id' && '<?php echo $filters['from_section_id']; ?>' == section.id) {
                    option.selected = true;
                }
                sectionSelect.appendChild(option);
            });
        });
}

function toggleAll() {
    const master = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.student-check');
    checkboxes.forEach(cb => cb.checked = master.checked);
}

<?php if ($filters['from_class_id']): ?>
window.onload = function() { loadSections(<?php echo $filters['from_class_id']; ?>, 'from_section_id'); };
<?php endif; ?>
</script>
