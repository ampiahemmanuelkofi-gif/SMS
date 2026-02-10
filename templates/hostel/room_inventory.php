<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-door-open"></i> Room & Bed Inventory</h2>
        <div class="btn-group">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHostelModal">
                <i class="bi bi-plus-circle me-1"></i> Add Hostel
            </button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="bi bi-plus-circle me-1"></i> Add Room
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Hostel List</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Hostel Name</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Rooms</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hostels as $h): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($h['hostel_name']); ?></strong></td>
                                <td><?php echo ucfirst($h['hostel_type']); ?></td>
                                <td><?php echo $h['capacity']; ?> Beds</td>
                                <td>15 Units</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Room Inventory</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Room No.</th>
                            <th>Hostel</th>
                            <th>Floor</th>
                            <th>Capacity</th>
                            <th>Occupied</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $r): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($r['room_number']); ?></strong></td>
                                <td><?php echo Security::clean($r['hostel_name']); ?></td>
                                <td><?php echo Security::clean($r['floor']); ?></td>
                                <td><?php echo $r['capacity']; ?></td>
                                <td>3</td>
                                <td><?php echo $r['capacity'] - 3; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Hostel Modal -->
<div class="modal fade" id="addHostelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Hostel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>hostel/inventory" method="POST">
                <input type="hidden" name="action" value="add_hostel">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hostel Name</label>
                        <input type="text" name="hostel_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="hostel_type" class="form-select" required>
                            <option value="boys">Boys</option>
                            <option value="girls">Girls</option>
                            <option value="mixed">Mixed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Capacity (Beds)</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Hostel</button>
                </div>
            </form>
        </div>
    </div>
</div>
