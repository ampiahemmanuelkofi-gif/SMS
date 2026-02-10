<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-person-check"></i> Transport Assignments</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Assign Student/Staff</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>transport/assignments" method="POST">
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">Select User</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>">
                                    <?php echo Security::clean($s['full_name']); ?> (<?php echo $s['username']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Route</label>
                        <select name="route_id" class="form-select" required>
                            <option value="">Select Route</option>
                            <?php foreach ($routes as $r): ?>
                                <option value="<?php echo $r['id']; ?>">
                                    <?php echo Security::clean($r['route_name']); ?> (GHS <?php echo $r['base_fare']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Assignment</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Current Assignments</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Route</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            <th>Joined On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $a): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($a['user_name']); ?></strong></td>
                                <td><?php echo Security::clean($a['route_name']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $a['plate_number'] ?: 'N/A'; ?></span></td>
                                <td>
                                    <span class="badge <?php echo $a['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($a['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($a['start_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($assignments)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No assignments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
