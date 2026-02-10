<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-bar-chart-steps"></i> Grading Scales</h2>
        <p class="text-muted">Define the score ranges and associated grades for terminal reports.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Grade</th>
                            <th>Range (%)</th>
                            <th>Remark</th>
                            <th>Points</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scales as $s): ?>
                            <tr>
                                <td><span class="badge bg-dark fw-bold fs-6"><?php echo $s['grade']; ?></span></td>
                                <td><span class="fw-bold"><?php echo $s['min_score']; ?>%</span> to <span class="fw-bold"><?php echo $s['max_score']; ?>%</span></td>
                                <td><?php echo Security::clean($s['remark']); ?></td>
                                <td><?php echo $s['point_value']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h5 class="fw-bold mb-3">System Note</h5>
                <p class="small text-muted">
                    Grades are automatically calculated during report card generation based on these ranges. 
                    The system uses the <strong>Weighted Average</strong> of all contribution percentages defined in Exam Types.
                </p>
                <div class="d-grid">
                    <button class="btn btn-outline-dark btn-sm">Download Scale PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>
