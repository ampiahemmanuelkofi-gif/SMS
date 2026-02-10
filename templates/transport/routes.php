<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-map"></i> Route Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Create New Route</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>transport/routes" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Route Name</label>
                        <input type="text" name="route_name" class="form-control" placeholder="e.g., East Legon Express" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Point</label>
                        <input type="text" name="start_point" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Point</label>
                        <input type="text" name="end_point" class="form-control" value="School Campus" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monthly Fare (GHS)</label>
                        <input type="number" step="0.01" name="base_fare" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned Vehicle</label>
                        <select name="vehicle_id" class="form-select" required>
                            <option value="">Select Vehicle</option>
                            <?php foreach ($vehicles as $v): ?>
                                <option value="<?php echo $v['id']; ?>">
                                    <?php echo Security::clean($v['plate_number']); ?> (<?php echo $v['vehicle_model']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Route</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Existing Routes</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Route Name</th>
                            <th>Start -> End</th>
                            <th>Vehicle</th>
                            <th>Fare</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($routes as $route): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($route['route_name']); ?></strong></td>
                                <td>
                                    <small><?php echo Security::clean($route['start_point']); ?></small><br>
                                    <i class="bi bi-arrow-down text-muted"></i><br>
                                    <small><?php echo Security::clean($route['end_point']); ?></small>
                                </td>
                                <td>
                                    <?php if ($route['vehicle_id']): ?>
                                        <span class="badge bg-info text-dark"><?php echo Security::clean($route['plate_number']); ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>GHS <?php echo number_format($route['base_fare'], 2); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
