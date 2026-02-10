<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">School Rooms</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="bi bi-plus-lg"></i> Add Room
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card">
            <?php if (empty($rooms)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-building display-4 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No rooms added yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Room Name</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Date Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($room['name']); ?></strong></td>
                                    <td><span class="badge bg-secondary"><?php echo ucfirst($room['type']); ?></span></td>
                                    <td><?php echo $room['capacity']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($room['created_at'])); ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" disabled><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>resources/rooms" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Room Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Science Lab 1, Hall A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="classroom">Classroom</option>
                            <option value="lab">Lab</option>
                            <option value="library">Library</option>
                            <option value="hall">Hall</option>
                            <option value="field">Field</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="30">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
