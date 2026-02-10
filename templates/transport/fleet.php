<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-truck"></i> Fleet Management</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            <i class="bi bi-plus-circle"></i> Add New Vehicle
        </button>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($vehicles as $vehicle): ?>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge <?php 
                            echo $vehicle['status'] == 'active' ? 'bg-success' : ($vehicle['status'] == 'maintenance' ? 'bg-warning' : 'bg-danger'); 
                        ?>">
                            <?php echo ucfirst($vehicle['status']); ?>
                        </span>
                        <h5 class="mb-0 text-primary fw-bold"><?php echo Security::clean($vehicle['plate_number']); ?></h5>
                    </div>
                    <h6 class="card-subtitle mb-3 text-muted"><?php echo Security::clean($vehicle['vehicle_model']); ?></h6>
                    
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-people text-muted me-2"></i> <strong>Capacity:</strong> <?php echo $vehicle['capacity']; ?> Seater
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-person-badge text-muted me-2"></i> <strong>Driver:</strong> <?php echo Security::clean($vehicle['driver_name']); ?>
                        </li>
                        <li>
                            <i class="bi bi-telephone text-muted me-2"></i> <strong>Phone:</strong> <?php echo Security::clean($vehicle['driver_phone']); ?>
                        </li>
                    </ul>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>transport/maintenance?vehicle=<?php echo $vehicle['id']; ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-tools"></i> View Maintenance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>transport/fleet" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Plate Number</label>
                        <input type="text" name="plate_number" class="form-control" placeholder="GV-1024-22" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle Model</label>
                        <input type="text" name="vehicle_model" class="form-control" placeholder="Toyota Coaster" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity (Seats)</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Phone</label>
                        <input type="text" name="driver_phone" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
