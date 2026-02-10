<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-journal-check"></i> Marks Entry</h2>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>assessments/entry" method="GET" class="row g-3">
        <div class="col-md-2">
            <label class="form-label">Term</label>
            <select name="term_id" class="form-select" required>
                <?php foreach ($terms as $t): ?>
                    <option value="<?php echo $t['id']; ?>" <?php echo $filters['term_id'] == $t['id'] ? 'selected' : ''; ?>>
                        <?php echo $t['year_name'] . ' - ' . $t['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" onchange="loadSections(this.value)" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $filters['class_id'] == $c['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Section</label>
            <select name="section_id" id="section_id" class="form-select" required>
                <option value="">Select Section</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $filters['subject_id'] == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($s['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="class_score" <?php echo $filters['type'] == 'class_score' ? 'selected' : ''; ?>>Class Score (30%)</option>
                <option value="exam" <?php echo $filters['type'] == 'exam' ? 'selected' : ''; ?>>Exam (70%)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Load Marks
            </button>
        </div>
    </form>
</div>

<?php if (!empty($students)): ?>
    <div class="table-card">
        <form action="<?php echo BASE_URL; ?>assessments/save" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="section_id" value="<?php echo $filters['section_id']; ?>">
            <input type="hidden" name="class_id" value="<?php echo $filters['class_id']; ?>">
            <input type="hidden" name="term_id" value="<?php echo $filters['term_id']; ?>">
            <input type="hidden" name="subject_id" value="<?php echo $filters['subject_id']; ?>">
            <input type="hidden" name="category_id" value="<?php echo $filters['category_id']; ?>">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="120">Student ID</th>
                            <th>Student Name</th>
                            <th width="150">Marks</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <?php 
                            $mark = $existingMarks[$student['id']] ?? ''; 
                            // TODO: Add logic to fetch dynamic remark based on grading scale if needed
                            $remark = '-';
                            ?>
                            <tr>
                                <td><?php echo Security::clean($student['student_id']); ?></td>
                                <td><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td>
                                    <input type="number" step="0.5" name="marks[<?php echo $student['id']; ?>]" 
                                           class="form-control" value="<?php echo $mark; ?>" 
                                           min="0">
                                </td>
                                <td>
                                    <span class="text-muted small"><?php echo $remark; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-5">
                    <i class="bi bi-save"></i> Save All Marks
                </button>
            </div>
        </form>
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

<?php if ($filters['class_id']): ?>
window.onload = function() { loadSections(<?php echo $filters['class_id']; ?>); };
<?php endif; ?>
</script>
