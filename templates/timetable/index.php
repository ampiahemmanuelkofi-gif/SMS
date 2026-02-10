<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">Class Timetable</h2>
        <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
            <a href="<?php echo BASE_URL; ?>timetable/manage?class_id=<?php echo $selectedClassId; ?>" class="btn btn-outline-primary">
                <i class="bi bi-gear"></i> Manage Timetable
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <label class="form-label">Select Class</label>
                <form action="<?php echo BASE_URL; ?>timetable" method="GET">
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>" <?php echo $selectedClassId == $class['id'] ? 'selected' : ''; ?>>
                                <?php echo Security::clean($class['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if ($selectedClassId && !empty($timetable)): ?>
    <?php
    // Organize by day
    $schedule = [
        'Monday' => [], 'Tuesday' => [], 'Wednesday' => [], 'Thursday' => [], 'Friday' => []
    ];
    $times = [];
    
    foreach ($timetable as $entry) {
        $schedule[$entry['day_of_week']][] = $entry;
    }
    
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    ?>
    
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Time / Day</th>
                        <?php foreach ($days as $day): ?>
                            <th><?php echo $day; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Simplified view: Assuming standard periods. For dynamic, we'd need a more complex iteration logic -->
                    <tr>
                        <td class="align-middle text-muted">Morning</td>
                        <?php foreach ($days as $day): ?>
                            <td class="align-top">
                                <?php if (!empty($schedule[$day])): ?>
                                    <?php foreach ($schedule[$day] as $slot): ?>
                                        <div class="p-2 mb-2 bg-light border rounded text-start">
                                            <div class="fw-bold text-primary"><?php echo Security::clean($slot['subject_name']); ?></div>
                                            <div class="small">
                                                <i class="bi bi-clock"></i> <?php echo date('H:i', strtotime($slot['start_time'])) . ' - ' . date('H:i', strtotime($slot['end_time'])); ?>
                                            </div>
                                            <?php if ($slot['room_name']): ?>
                                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?php echo Security::clean($slot['room_name']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php elseif ($selectedClassId): ?>
    <div class="alert alert-info py-4 text-center">
        No schedule found for this class.
    </div>
<?php endif; ?>
