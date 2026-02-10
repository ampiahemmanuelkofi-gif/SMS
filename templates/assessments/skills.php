

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-person-check"></i> Skills Assessment</h2>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>assessments/skills" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Term</label>
            <select name="term_id" class="form-select" required>
                <?php foreach ($terms as $t): ?>
                    <option value="<?php echo $t['id']; ?>" <?php echo $filters['term_id'] == $t['id'] ? 'selected' : ''; ?>>
                        <?php echo $t['year_name'] . ' - ' . $t['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <label class="form-label">Section</label>
            <select name="section_id" id="section_id" class="form-select" required>
                <option value="">Select Section</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Load Students
            </button>
        </div>
    </form>
</div>

<?php if (!empty($students)): ?>
    <div class="table-card">
        <form action="<?php echo BASE_URL; ?>assessments/saveSkills" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="section_id" value="<?php echo $filters['section_id']; ?>">
            <input type="hidden" name="class_id" value="<?php echo $filters['class_id']; ?>">
            <input type="hidden" name="term_id" value="<?php echo $filters['term_id']; ?>">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class="sticky-left">Student</th>
                            <?php foreach ($skills as $skill): ?>
                                <th class="text-center" style="min-width: 100px;">
                                    <?php echo $skill['name']; ?>
                                    <small class="d-block text-muted fw-normal" style="font-size: 10px;">(1-5)</small>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="sticky-left fw-bold">
                                    <?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?>
                                </td>
                                <?php foreach ($skills as $skill): ?>
                                    <td>
                                        <input type="number" name="ratings[<?php echo $student['id']; ?>][<?php echo $skill['id']; ?>]" 
                                               class="form-control form-control-sm text-center" 
                                               value="<?php echo $existingRatings[$student['id']][$skill['id']] ?? ''; ?>" 
                                               min="1" max="5">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-5">
                    <i class="bi bi-save"></i> Save Skills
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<style>
    .sticky-left {
        position: sticky;
        left: 0;
        background: white;
        z-index: 10;
        border-right: 2px solid #dee2e6;
    }
</style>

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
