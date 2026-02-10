<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-mortarboard-fill"></i> BECE Results Entry (JHS 3)</h2>
        <p class="text-muted">Enter final WAEC BECE grades for graduating students.</p>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>assessments/bece" method="GET" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Academic Year</label>
            <select name="year_id" class="form-select" required>
                <?php foreach ($years as $yr): ?>
                    <option value="<?php echo $yr['id']; ?>" <?php echo $filters['year_id'] == $yr['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($yr['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Section (JHS 3 Classes)</label>
            <select name="section_id" class="form-select" required>
                <option value="">Select Section</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?php echo $sec['id']; ?>" <?php echo $filters['section_id'] == $sec['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($sec['class_name'] . ' ' . $sec['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Load Students
            </button>
        </div>
    </form>
</div>

<?php if (!empty($students)): ?>
    <div class="table-card">
        <form action="<?php echo BASE_URL; ?>assessments/save_bece" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="year_id" value="<?php echo $filters['year_id']; ?>">
            <input type="hidden" name="section_id" value="<?php echo $filters['section_id']; ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>English</th>
                            <th>Maths</th>
                            <th>Science</th>
                            <th>Social</th>
                            <th>Elective 1</th>
                            <th>Elective 2</th>
                            <th>Agg.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <?php $res = $results[$student['id']] ?? []; ?>
                            <tr>
                                <td>
                                    <strong><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></strong><br>
                                    <small class="text-muted"><?php echo $student['student_id']; ?></small>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][english]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['english_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][maths]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['maths_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][science]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['science_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][social]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['social_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][elective1]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['elective1_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="results[<?php echo $student['id']; ?>][elective2]" class="form-select form-select-sm grade-select" data-student="<?php echo $student['id']; ?>">
                                        <option value="">-</option>
                                        <?php for($i=1; $i<=9; $i++) echo "<option value='".getGradeName($i)."' ".($res['elective2_grade'] == getGradeName($i) ? 'selected' : '').">".getGradeName($i)."</option>"; ?>
                                    </select>
                                </td>
                                <td>
                                    <span id="agg-<?php echo $student['id']; ?>" class="fw-bold text-primary"><?php echo $res['aggregate'] ?? '--'; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-5">
                    <i class="bi bi-save"></i> Save BECE Results
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php
function getGradeName($i) {
    $types = [1=>'A1', 2=>'B2', 3=>'B3', 4=>'C4', 5=>'C5', 6=>'C6', 7=>'D7', 8=>'E8', 9=>'F9'];
    return $types[$i];
}
?>

<script>
document.querySelectorAll('.grade-select').forEach(select => {
    select.addEventListener('change', function() {
        const studentId = this.dataset.student;
        const selects = document.querySelectorAll(`.grade-select[data-student="${studentId}"]`);
        let points = [];
        selects.forEach(s => {
            if (s.value) {
                // Extract number from A1, B2 etc
                points.push(parseInt(s.value.substring(1)));
            }
        });
        
        if (points.length >= 6) {
            points.sort((a, b) => a - b);
            const aggregate = points.slice(0, 6).reduce((a, b) => a + b, 0);
            document.getElementById(`agg-${studentId}`).textContent = aggregate;
        } else {
            document.getElementById(`agg-${studentId}`).textContent = '--';
        }
    });
});
</script>
