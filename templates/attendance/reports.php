<?php
/**
 * Attendance Reports View
 */
?>
<div class="row mb-4 row align-items-center">
    <div class="col">
        <h2 class="page-title"><i class="bi bi-bar-chart-fill"></i> Attendance Reports</h2>
    </div>
</div>

<div class="table-card mb-4">
    <form action="<?php echo BASE_URL; ?>attendance/reports" method="GET" class="row g-3">
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
        <div class="col-md-3">
            <label class="form-label">Month</label>
            <input type="month" name="month" class="form-control" value="<?php echo $filters['month'] ?: date('Y-m'); ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Generate Report
            </button>
        </div>
    </form>
</div>

<?php if (!empty($reportData)): ?>
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Monthly Attendance Summary: <?php echo date('F Y', strtotime($filters['month'])); ?></h5>
            <button onclick="exportToCSV()" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">Late</th>
                        <th class="text-center">Percentage</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reportData as $row): ?>
                        <?php 
                        $total = $row['present'] + $row['absent'] + $row['late'];
                        $percent = $total > 0 ? round(($row['present'] / $total) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo Security::clean($row['name']); ?></td>
                            <td class="text-center"><?php echo $row['present']; ?></td>
                            <td class="text-center text-danger"><?php echo $row['absent']; ?></td>
                            <td class="text-center text-warning"><?php echo $row['late']; ?></td>
                            <td class="text-center fw-bold"><?php echo $percent; ?>%</td>
                            <td>
                                <?php if($percent >= 90): ?>
                                    <span class="badge bg-success">Excellent</span>
                                <?php elseif($percent >= 75): ?>
                                    <span class="badge bg-primary">Good</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Risk</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                <?php if ($filters['section_id']): ?>
                if (section.id == '<?php echo $filters['section_id']; ?>') option.selected = true;
                <?php endif; ?>
                sectionSelect.appendChild(option);
            });
        });
}

<?php if ($filters['class_id']): ?>
window.onload = function() { loadSections(<?php echo $filters['class_id']; ?>); };
<?php endif; ?>
</script>
