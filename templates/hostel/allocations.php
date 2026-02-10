<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-people"></i> Bed Allocations</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#allocateModal">
            <i class="bi bi-person-plus me-1"></i> New Allocation
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Current Active Allocations</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Hostel / Room / Bed</th>
                            <th>Allotted Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allocations as $a): ?>
                            <tr>
                                <td>
                                    <h6 class="mb-0 fw-bold"><?php echo Security::clean($a['student_name']); ?></h6>
                                </td>
                                <td>
                                    <div><?php echo Security::clean($a['hostel_name']); ?></div>
                                    <small class="text-muted">Room: <?php echo $a['room_number']; ?> | Bed: <?php echo $a['bed_number']; ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($a['allotted_on'])); ?></td>
                                <td>
                                    <form action="<?php echo BASE_URL; ?>hostel/allocations" method="POST" onsubmit="return confirm('Vacate this bed?')">
                                        <input type="hidden" name="action" value="vacate">
                                        <input type="hidden" name="allocation_id" value="<?php echo $a['id']; ?>">
                                        <input type="hidden" name="bed_id" value="<?php echo $a['bed_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Vacate Bed</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($allocations)): ?>
                            <tr><td colspan="4" class="text-center py-5">No active allocations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Allocate Modal -->
<div class="modal fade" id="allocateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Assign Bed to Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>hostel/allocations" method="POST">
                <input type="hidden" name="action" value="allocate">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Student</label>
                        <select name="student_id" class="form-select select2" required>
                            <option value="">Search student...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo Security::clean($s['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Bed</label>
                        <select name="bed_id" class="form-select" required>
                            <option value="">Choose available bed...</option>
                            <?php foreach ($available_beds as $b): ?>
                                <?php if ($b['status'] == 'available'): ?>
                                    <option value="<?php echo $b['id']; ?>">
                                        <?php echo Security::clean($b['hostel_name']); ?> - Rm <?php echo $b['room_number']; ?> - Bed <?php echo $b['bed_number']; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Allocation Date</label>
                        <input type="date" name="allotted_on" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Confirm Allocation</button>
                </div>
            </form>
        </div>
    </div>
</div>
