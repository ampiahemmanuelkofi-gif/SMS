<?php
/**
 * Report Card View / Print Template
 */
?>
<div class="d-print-none mb-4 row align-items-center">
    <div class="col">
        <h2 class="page-title"><i class="bi bi-printer"></i> Terminal Report Card</h2>
    </div>
    <div class="col-auto">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print Report Card
        </button>
    </div>
</div>

<div class="report-card printable-area p-4 bg-white border shadow-sm">
    <!-- Header -->
    <div class="text-center mb-4 border-bottom pb-4">
        <h3><?php echo strtoupper(SCHOOL_NAME); ?></h3>
        <p class="mb-1"><?php echo SCHOOL_ADDRESS; ?></p>
        <p class="mb-1">Tel: <?php echo SCHOOL_PHONE; ?> | Email: <?php echo SCHOOL_EMAIL; ?></p>
        <h4 class="mt-3 text-decoration-underline">TERMINAL PROGRESS REPORT</h4>
    </div>

    <!-- Student Info -->
    <div class="row mb-4">
        <div class="col-md-8">
            <table class="table table-borderless table-sm">
                <tr><th width="150">Student Name:</th><td><strong><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></strong></td></tr>
                <tr><th>Student ID:</th><td><?php echo $student['student_id']; ?></td></tr>
                <tr><th>Class:</th><td><?php echo $student['class_name'] . ' ' . $student['section_name']; ?></td></tr>
                <tr><th>Term:</th><td><?php echo $term['name'] . ' (' . $term['year_name'] . ')'; ?></td></tr>
            </table>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($student['photo']): ?>
                <img src="<?php echo BASE_URL . $student['photo']; ?>" class="rounded border" style="width: 100px; height: 100px; object-fit: cover;">
            <?php else: ?>
                <div class="border d-inline-block rounded p-3 text-muted" style="width: 100px; height: 100px;">No Photo</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Marks Table -->
    <table class="table table-bordered text-center align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th rowspan="2" class="text-start">Subject</th>
                <th colspan="2">Class Score (30%)</th>
                <th colspan="2">Exams (70%)</th>
                <th rowspan="2">Total Marks (100)</th>
                <th rowspan="2">Grade</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr>
                <th>Actual</th>
                <th>Weighted</th>
                <th>Actual</th>
                <th>Weighted</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalWeighted = 0;
            $subjectCount = 0;
            foreach ($subjectMarks as $sm): 
                $classWeighted = ($sm['class_score'] / 100) * 30; // Assuming input was /100 or scale it
                $examWeighted = ($sm['exam_score'] / 100) * 70;
                $total = $classWeighted + $examWeighted;
                $gradeData = getBeceGrade($total);
                $totalWeighted += $total;
                $subjectCount++;
            ?>
                <tr>
                    <td class="text-start"><?php echo Security::clean($sm['subject_name']); ?></td>
                    <td><?php echo $sm['class_score']; ?></td>
                    <td><?php echo number_format($classWeighted, 1); ?></td>
                    <td><?php echo $sm['exam_score']; ?></td>
                    <td><?php echo number_format($examWeighted, 1); ?></td>
                    <td class="fw-bold"><?php echo number_format($total, 1); ?></td>
                    <td><span class="badge bg-light text-dark border"><?php echo $gradeData['grade']; ?></span></td>
                    <td class="small"><?php echo $gradeData['remark']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Attendance and Summary -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="border p-3 rounded">
                <h6 class="border-bottom pb-2">Attendance Summary</h6>
                <table class="table table-sm table-borderless">
                    <tr><th>Total Days:</th><td><?php echo $attendance['total']; ?></td></tr>
                    <tr><th>Present:</th><td class="text-success"><?php echo $attendance['present']; ?></td></tr>
                    <tr><th>Absent:</th><td class="text-danger"><?php echo $attendance['absent']; ?></td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border p-3 rounded">
                <h6 class="border-bottom pb-2">Academic Standing</h6>
                <table class="table table-sm table-borderless">
                    <tr><th>Average Mark:</th><td><strong><?php echo $subjectCount > 0 ? number_format($totalWeighted / $subjectCount, 1) : '0.0'; ?>%</strong></td></tr>
                    <tr><th>Conduct:</th><td>Satisfactory</td></tr>
                    <tr><th>Teacher's Comment:</th><td class="text-muted italic">____________________</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="row mt-5 pt-4 text-center">
        <div class="col-4">
            <div class="border-top pt-2">Class Teacher</div>
        </div>
        <div class="col-4">
            <div class="border-top pt-2">Head Teacher</div>
        </div>
        <div class="col-4">
            <div class="border-top pt-2">Date Issued</div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .printable-area, .printable-area * { visibility: visible; }
    .printable-area { position: absolute; left: 0; top: 0; width: 100%; border: none !important; }
    .d-print-none { display: none !important; }
}
</style>
