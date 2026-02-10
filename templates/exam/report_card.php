<div class="text-center mb-5 printable-area">
    <h1 class="fw-bold text-primary"><?php echo defined('SCHOOL_NAME') ? SCHOOL_NAME : 'School Management System'; ?></h1>
    <h4 class="text-muted text-uppercase fw-bold">Terminal Academic Report</h4>
    <hr style="width: 100px; margin: 20px auto; border-top: 3px solid #0d6efd;">
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="p-4 bg-light rounded-3 border-start border-4 border-primary">
            <h6 class="text-uppercase text-muted small fw-bold">Student Profile</h6>
            <h4 class="fw-bold mb-1"><?php echo Security::clean($student['full_name']); ?></h4>
            <p class="mb-0 text-muted">Student ID: <?php echo $student['username']; ?></p>
        </div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="p-4">
            <h6 class="text-uppercase text-muted small fw-bold">Academic Session</h6>
            <h5 class="fw-bold">First Term, 2023/2024</h5>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th rowspan="2" class="align-middle">Subject</th>
                    <th colspan="3">Continuous Assessment (50%)</th>
                    <th rowspan="2" class="align-middle">Exam (50%)</th>
                    <th rowspan="2" class="align-middle">Total (100%)</th>
                    <th rowspan="2" class="align-middle">Grade</th>
                    <th rowspan="2" class="align-middle">Remarks</th>
                </tr>
                <tr class="small">
                    <th>Test</th>
                    <th>Lab</th>
                    <th>Proj</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grandTotal = 0;
                $subjectCount = 0;
                foreach ($aggregated as $subject => $data): 
                    $gradeInfo = $model->calculateGrade($data['weighted_total']);
                    $grandTotal += $data['weighted_total'];
                    $subjectCount++;
                ?>
                    <tr>
                        <td><strong><?php echo Security::clean($subject); ?></strong></td>
                        <td class="text-center">15/20</td> <!-- Placeholder for sub-details -->
                        <td class="text-center">10/10</td>
                        <td class="text-center">15/20</td>
                        <td class="text-center">45/50</td>
                        <td class="text-center fw-bold text-primary"><?php echo number_format($data['weighted_total'], 1); ?></td>
                        <td class="text-center"><span class="badge bg-dark"><?php echo $gradeInfo['grade']; ?></span></td>
                        <td><small><?php echo $gradeInfo['remark']; ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td colspan="5" class="text-end">Average Score:</td>
                    <td class="text-center text-primary"><?php echo ($subjectCount > 0) ? number_format($grandTotal / $subjectCount, 2) : '0.00'; ?>%</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="p-3 border rounded">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Class Teacher's Remark</h6>
            <p class="text-muted italic">"<?php echo Security::clean($student['full_name']); ?> is a disciplined student with great potential. Keep up the hard work."</p>
            <div class="mt-4 border-top pt-2 text-center" style="max-width: 200px;">
                <small>Signature</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-dark shadow-sm px-4 d-print-none" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print Report Card
        </button>
    </div>
</div>
