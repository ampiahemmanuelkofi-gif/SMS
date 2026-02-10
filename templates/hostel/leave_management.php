<!-- slide -->
<!-- leave_management.php content -->
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-box-arrow-right"></i> Boarder Leave Management</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Leave & Outing Requests</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Duration (Out - Expected In)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($r['student_name']); ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?php echo ucfirst($r['leave_type']); ?></span></td>
                                <td>
                                    <small><?php echo date('M d, H:i', strtotime($r['out_date'])); ?></small>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <small><?php echo date('M d, H:i', strtotime($r['expected_in_date'])); ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $class = [
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-info',
                                        'rejected' => 'bg-danger',
                                        'returned' => 'bg-success'
                                    ][$r['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $class; ?>"><?php echo ucfirst($r['status']); ?></span>
                                </td>
                                <td>
                                    <?php if ($r['status'] == 'pending'): ?>
                                        <form action="<?php echo BASE_URL; ?>hostel/leave" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                            <input type="hidden" name="action" value="status">
                                            <button name="status" value="approved" class="btn btn-sm btn-success"><i class="bi bi-check me-1"></i>Approve</button>
                                            <button name="status" value="rejected" class="btn btn-sm btn-danger"><i class="bi bi-x me-1"></i>Reject</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- slide -->
<!-- incidents.php content will be created in a separate file if needed, but let's consolidate for now if possible or create another tool call -->
