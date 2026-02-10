

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subject Weights Configuration</h1>
        <a href="<?php echo BASE_URL; ?>assessments/setup" class="btn btn-sm btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Setup
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Context</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo BASE_URL; ?>assessments/setup/weights" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $filters['class_id'] == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo $c['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo $filters['subject_id'] == $s['id'] ? 'selected' : ''; ?>>
                                <?php echo $s['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Term</label>
                    <select name="term_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Select Term</option>
                        <?php foreach ($terms as $t): ?>
                            <option value="<?php echo $t['id']; ?>" <?php echo $filters['term_id'] == $t['id'] ? 'selected' : ''; ?>>
                                <?php echo $t['name'] . ' (' . $t['year_name'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($filters['class_id'] && $filters['subject_id']): ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configure Weights</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>assessments/setup/saveWeights" method="POST">
                        <input type="hidden" name="class_id" value="<?php echo $filters['class_id']; ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $filters['subject_id']; ?>">
                        <input type="hidden" name="term_id" value="<?php echo $filters['term_id']; ?>">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Weight (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    foreach ($categories as $cat): 
                                        $val = 0;
                                        foreach ($currentWeights as $cw) {
                                            if ($cw['category_id'] == $cat['id']) {
                                                $val = $cw['weight_percent'];
                                                break;
                                            }
                                        }
                                        $total += $val;
                                    ?>
                                    <tr>
                                        <td><?php echo $cat['name']; ?></td>
                                        <td>
                                            <input type="number" step="0.01" name="weights[<?php echo $cat['id']; ?>]" 
                                                   class="form-control weight-input" 
                                                   value="<?php echo $val; ?>" min="0" max="100">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-secondary font-weight-bold">
                                        <td>Total</td>
                                        <td id="totalWeight"><?php echo $total; ?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info small">
                            Total must equal 100% to save correctly.
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Save Configuration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    const inputs = document.querySelectorAll('.weight-input');
    const totalDisplay = document.getElementById('totalWeight');
    
    function calculateTotal() {
        let total = 0;
        inputs.forEach(input => {
            total += parseFloat(input.value || 0);
        });
        totalDisplay.textContent = total.toFixed(2) + '%';
        
        if (Math.abs(total - 100) > 0.01) {
            totalDisplay.classList.add('text-danger');
            totalDisplay.classList.remove('text-success');
        } else {
            totalDisplay.classList.remove('text-danger');
            totalDisplay.classList.add('text-success');
        }
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
</script>
