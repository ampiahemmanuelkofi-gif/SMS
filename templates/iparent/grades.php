<div class="card-mobile p-3 bg-white mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Recent Assessment</h6>
        <span class="badge bg-soft-primary text-primary">Term 2</span>
    </div>
    <div class="mt-3 text-center">
        <h3 class="fw-bold mb-0">92/100</h3>
        <div class="text-muted small">Mathematics Quiz #3</div>
    </div>
</div>

<h6 class="fw-bold mb-3">Subject Performance</h6>
<div class="card-mobile bg-white p-0 overflow-hidden">
    <?php 
    $grades = [
        ['subject' => 'Mathematics', 'grade' => 'A', 'score' => 92, 'color' => 'success'],
        ['subject' => 'Physics', 'grade' => 'B+', 'score' => 84, 'color' => 'primary'],
        ['subject' => 'English', 'grade' => 'A-', 'score' => 89, 'color' => 'info'],
        ['subject' => 'History', 'grade' => 'C+', 'score' => 74, 'color' => 'warning']
    ];
    foreach ($grades as $g): ?>
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="fw-bold"><?php echo $g['subject']; ?></div>
        <div class="d-flex align-items-center">
            <div class="me-3 text-end">
                <div class="fw-bold text-<?php echo $g['color']; ?>"><?php echo $g['grade']; ?></div>
                <div class="progress" style="height: 4px; width: 60px;">
                    <div class="progress-bar bg-<?php echo $g['color']; ?>" role="progressbar" style="width: <?php echo $g['score']; ?>%"></div>
                </div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="d-grid mt-4">
    <button class="btn btn-outline-primary card-mobile py-2">
        <i class="bi bi-file-earmark-pdf"></i> Download Full Report (PDF)
    </button>
</div>
