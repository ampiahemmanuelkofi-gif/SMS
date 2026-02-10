<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-file-earmark-pdf-fill"></i> System Reports</h2>
        <p class="text-muted">Generate and view detailed reports for school operations.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Financial Reports -->
    <div class="col-md-4">
        <div class="table-card text-center py-4 h-100 shadow-sm border-0 border-top border-primary border-4">
            <div class="mb-3">
                <i class="bi bi-cash-coin text-primary" style="font-size: 3rem;"></i>
            </div>
            <h4>Financial Reports</h4>
            <p class="text-muted px-3">View fee collection summaries, payment methods, and daily revenue.</p>
            <div class="d-flex flex-column gap-2 px-3">
                <a href="<?php echo BASE_URL; ?>reports/finance" class="btn btn-primary">General Collections</a>
                <a href="<?php echo BASE_URL; ?>reports/pnl" class="btn btn-outline-success">Profit & Loss Statement</a>
            </div>
        </div>
    </div>
    
    <!-- Academic/Student Reports -->
    <div class="col-md-4">
        <div class="table-card text-center py-4 h-100 shadow-sm border-0 border-top border-success border-4">
            <div class="mb-3">
                <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
            </div>
            <h4>Student Statistics</h4>
            <p class="text-muted px-3">Enrollment trends, gender distribution, and class-wise population.</p>
            <a href="<?php echo BASE_URL; ?>reports/students" class="btn btn-success mt-auto">Open Student Stats</a>
        </div>
    </div>
    
    <!-- Attendance Reports -->
    <div class="col-md-4">
        <div class="table-card text-center py-4 h-100 shadow-sm border-0 border-top border-warning border-4">
            <div class="mb-3">
                <i class="bi bi-calendar-check-fill text-warning" style="font-size: 3rem;"></i>
            </div>
            <h4>Attendance Reports</h4>
            <p class="text-muted px-3">Monthly attendance summaries and absenteeism tracking.</p>
            <a href="<?php echo BASE_URL; ?>attendance/reports" class="btn btn-warning mt-auto">Open Attendance Reports</a>
        </div>
    </div>
</div>
