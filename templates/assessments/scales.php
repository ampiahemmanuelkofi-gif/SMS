

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Grading Scales: <?php echo $system['name']; ?></h1>
        <a href="<?php echo BASE_URL; ?>assessments/setup" class="btn btn-sm btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Setup
        </a>
    </div>

    <div class="row">
        <!-- Add/Edit Form -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add/Edit Scale</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>assessments/setup/saveScale" method="POST">
                        <input type="hidden" name="system_id" value="<?php echo $system['id']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Grade Label</label>
                            <input type="text" name="grade" class="form-control" placeholder="e.g. A1" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Min Score</label>
                                <input type="number" step="0.01" name="min_score" class="form-control" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Max Score</label>
                                <input type="number" step="0.01" name="max_score" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">GPA Point</label>
                            <input type="number" step="0.01" name="gpa_point" class="form-control" value="0.00">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <input type="text" name="remark" class="form-control" placeholder="e.g. Excellent">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            Save Scale
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Existing Scales</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Range</th>
                                    <th>GPA</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scales as $scale): ?>
                                <tr>
                                    <td class="font-weight-bold"><?php echo $scale['grade']; ?></td>
                                    <td><?php echo $scale['min_score']; ?> - <?php echo $scale['max_score']; ?></td>
                                    <td><?php echo $scale['gpa_point']; ?></td>
                                    <td><?php echo $scale['remark']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn" 
                                            data-id="<?php echo $scale['id']; ?>"
                                            data-grade="<?php echo $scale['grade']; ?>"
                                            data-min="<?php echo $scale['min_score']; ?>"
                                            data-max="<?php echo $scale['max_score']; ?>"
                                            data-gpa="<?php echo $scale['gpa_point']; ?>"
                                            data-remark="<?php echo $scale['remark']; ?>">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Populate form (simple implementation)
        const form = document.querySelector('form');
        form.querySelector('[name="grade"]').value = btn.dataset.grade;
        form.querySelector('[name="min_score"]').value = btn.dataset.min;
        form.querySelector('[name="max_score"]').value = btn.dataset.max;
        form.querySelector('[name="gpa_point"]').value = btn.dataset.gpa;
        form.querySelector('[name="remark"]').value = btn.dataset.remark;
        
        // Add hidden id field if not exists
        let idField = form.querySelector('[name="id"]');
        if (!idField) {
            idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            form.appendChild(idField);
        }
        idField.value = btn.dataset.id;
    });
});
</script>
