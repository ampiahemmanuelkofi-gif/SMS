<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-person-badge"></i> Teacher Assignments</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
            <i class="bi bi-person-plus"></i> New Assignment
        </button>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assignments)): ?>
                    <?php foreach ($assignments as $asgn): ?>
                        <tr>
                            <td><strong><?php echo Security::clean($asgn['teacher_name']); ?></strong></td>
                            <td><?php echo Security::clean($asgn['class_name']); ?></td>
                            <td><?php echo Security::clean($asgn['section_name']); ?></td>
                            <td><?php echo Security::clean($asgn['subject_name']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" title="Remove Assignment">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No assignments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>academics/assignTeacher" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Teacher to Class/Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">Select Teacher</option>
                            <?php
                            $db = getDbConnection();
                            $teachers = $db->query("SELECT id, full_name FROM users WHERE role = 'teacher' AND is_active = 1 ORDER BY full_name");
                            while ($t = $teachers->fetch()):
                            ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo Security::clean($t['full_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select id="assign_class_id" class="form-select" onchange="loadSections(this.value)" required>
                            <option value="">Select Class</option>
                            <?php
                            $classes = $db->query("SELECT * FROM classes ORDER BY level, name");
                            while ($c = $classes->fetch()):
                            ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo Security::clean($c['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select name="section_id" id="assign_section_id" class="form-select" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            <?php
                            $subjects = $db->query("SELECT id, name, level FROM subjects ORDER BY level, name");
                            while ($s = $subjects->fetch()):
                            ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['name']); ?> (<?php echo ucfirst($s['level']); ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('assign_section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
        });
}
</script>
