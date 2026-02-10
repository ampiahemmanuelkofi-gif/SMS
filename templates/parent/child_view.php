<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title"><i class="bi bi-person-circle"></i> Child Profile: <?php echo Security::clean($student['first_name']); ?></h2>
            <p class="text-muted">Detailed academic and financial overview for your child.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>parent/children" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Student Info & Balance -->
    <div class="col-md-4">
        <div class="table-card shadow-sm border-0 mb-4">
            <div class="text-center py-3">
                <img src="<?php echo $student['photo'] ? BASE_URL . $student['photo'] : BASE_URL . 'assets/images/placeholder.png'; ?>" 
                     class="rounded-circle shadow-sm border mb-3" width="120" height="120" style="object-fit: cover;">
                <h4><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></h4>
                <p class="text-muted mb-0"><?php echo $student['student_id']; ?></p>
            </div>
            <hr>
            <div class="p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Current Class</span>
                    <span class="fw-bold"><?php echo Security::clean($student['class_name'] . ' ' . $student['section_name']); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Admission Date</span>
                    <span><?php echo date('d M Y', strtotime($student['admission_date'])); ?></span>
                </div>
                
                <div class="bg-light p-3 rounded text-center border">
                    <small class="text-muted d-block text-uppercase mb-1">Outstanding Balance</small>
                    <h3 class="fw-bold <?php echo $balance['balance'] > 0 ? 'text-danger' : 'text-success'; ?>">
                        GH₵ <?php echo number_format($balance['balance'], 2); ?>
                    </h3>
                    <a href="<?php echo BASE_URL; ?>parent/payments" class="btn btn-sm btn-link">View Payment History</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Academic Progress & Attendance -->
    <div class="col-md-8">
        <!-- Attendance Stats -->
        <div class="table-card shadow-sm border-0 mb-4">
            <h5>Attendance Summary</h5>
            <div class="row text-center mt-3 g-2">
                <?php 
                $totalDays = 0;
                foreach ($attendance as $a) $totalDays += $a['count'];
                foreach ($attendance as $a): 
                    $color = match($a['status']) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        default => 'secondary'
                    };
                ?>
                    <div class="col">
                        <div class="p-2 border rounded">
                            <h4 class="mb-0 fw-bold text-<?php echo $color; ?>"><?php echo $a['count']; ?></h4>
                            <small class="text-muted text-capitalize"><?php echo $a['status']; ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Recent Performance -->
        <div class="table-card shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Assessments</h5>
                <a href="<?php echo BASE_URL; ?>assessments/reportCard/<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-file-earmark-pdf"></i> Generate Report Card
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Score</th>
                            <th>Maximum</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assessments)): ?>
                            <tr><td colspan="5" class="text-center py-3 text-muted">No assessment records found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($assessments as $as): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo Security::clean($as['subject_name']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo ucfirst($as['assessment_type']); ?></span></td>
                                    <td class="fw-bold"><?php echo $as['marks_obtained']; ?></td>
                                    <td><?php echo (int)$as['marks_maximum']; ?></td>
                                    <td><small class="text-muted"><?php echo date('d/m/y', strtotime($as['created_at'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
