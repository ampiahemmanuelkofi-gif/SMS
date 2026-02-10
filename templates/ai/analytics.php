<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-cpu text-primary"></i> AI Intelligence Hub</h2>
        <p class="text-muted">Data-driven insights and automated administrative actions.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Active Anomalies -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Critical Anomalies Detected</h5>
                <span class="badge bg-danger"><?php echo count($anomalies); ?> Active</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Message</th>
                                <th>Severity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($anomalies as $a): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo ($a['type'] == 'attendance_drop') ? 'Attendance' : 'Academic'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo Security::clean($a['message']); ?></td>
                                    <td><span class="badge bg-danger">Critical</span></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>students/profile/<?php echo $a['student_id']; ?>" class="btn btn-sm btn-dark">Investigate</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($anomalies)): ?>
                                <tr><td colspan="4" class="text-center py-4">No critical anomalies detected. System is healthy.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Actions -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge me-2"></i> Automated Actions</h5>
                <p class="small opacity-75">The AI has identified <strong><?php echo count($fee_alerts); ?></strong> parents due for fee reminders based on their payment patterns.</p>
                <form action="<?php echo BASE_URL; ?>ai/sendReminders" method="POST">
                    <button type="submit" class="btn btn-light w-100 fw-bold">Dispatch Reminders</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Substitution Suggestions</h6>
                <p class="small text-muted">2 teachers are currently absent. Suggesting replacements based on subject expertise and availability.</p>
                <div class="d-grid">
                    <button class="btn btn-outline-dark btn-sm">View Suggestions</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Timetable Generation</h5>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="text-muted">Generate an optimized school timetable by resolving 150+ teacher-room constraints automatically.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button class="btn btn-dark"><i class="bi bi-calendar-range me-2"></i> Launch AI Scheduler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
