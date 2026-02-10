<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-clipboard-check"></i> Meal Check-off</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="mb-0">Current Meal Session</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>cafeteria/attendance" method="GET">
                    <div class="mb-3">
                        <select name="menu_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Select current meal...</option>
                            <?php foreach ($today_menu as $m): ?>
                                <option value="<?php echo $m['id']; ?>" <?php echo (isset($_GET['menu_id']) && $_GET['menu_id'] == $m['id']) ? 'selected' : ''; ?>>
                                    <?php echo $m['type_name']; ?>: <?php echo Security::clean($m['menu_item']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <?php if ($current_menu): ?>
                    <hr>
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold">Item Today</label>
                        <h5 class="fw-bold mb-0 text-primary"><?php echo Security::clean($current_menu['menu_item']); ?></h5>
                    </div>
                    <form action="<?php echo BASE_URL; ?>cafeteria/attendance" method="POST" id="attendanceForm">
                        <input type="hidden" name="menu_id" value="<?php echo $current_menu['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Scan or Select Student</label>
                            <select name="user_id" id="studentSelect" class="form-select select2" required onchange="checkAlerts(this)">
                                <option value="">Start typing student name...</option>
                                <?php foreach ($students as $s): ?>
                                    <option value="<?php echo $s['id']; ?>" data-alerts="<?php echo isset($dietary_alerts[$s['id']]) ? implode(', ', $dietary_alerts[$s['id']]) : ''; ?>">
                                        <?php echo Security::clean($s['full_name']); ?>
                                        <?php if (isset($dietary_alerts[$s['id']])): ?> [ALERT] <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="alertBox" class="alert alert-danger d-none mb-3">
                            <i class="bi bi-shield-exclamation me-2"></i>
                            <strong>Dietary Warning:</strong> <span id="alertText"></span>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"><i class="bi bi-check-lg me-2"></i> Mark Attended</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">Recent Sign-ins</h5>
                <span class="badge bg-primary rounded-pill px-3"><?php echo count($logs); ?> Served</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Time</th>
                            <th>Marked By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($logs) as $l): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($l['student_name']); ?></strong></td>
                                <td><?php echo date('H:i:s', strtotime($l['attended_at'])); ?></td>
                                <td><small class="text-muted"><?php echo Security::clean($l['staff_name']); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="3" class="text-center py-5 text-muted">No students checked in yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function checkAlerts(select) {
    const option = select.options[select.selectedIndex];
    const alerts = option.getAttribute('data-alerts');
    const alertBox = document.getElementById('alertBox');
    const alertText = document.getElementById('alertText');
    
    if (alerts) {
        alertText.innerText = alerts;
        alertBox.classList.remove('d-none');
        // Vibrate if on mobile
        if(window.navigator.vibrate) window.navigator.vibrate(200);
    } else {
        alertBox.classList.add('d-none');
    }
}
</script>
