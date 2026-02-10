<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-calendar-check"></i> Leave Management</h2>
        <?php if (!in_array(Auth::getRole(), ['super_admin', 'admin'])): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestLeaveModal">
                <i class="bi bi-plus-circle"></i> Request Leave
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (in_array(Auth::getRole(), ['super_admin', 'admin'])): ?>
    <div class="row g-4">
        <!-- Pending Approvals -->
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-warning"><i class="bi bi-clock-history"></i> Pending Approvals</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Period</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingRequests as $req): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($req['full_name']); ?></strong></td>
                                    <td><span class="badge bg-info-subtle text-info"><?php echo Security::clean($req['leave_type_name']); ?></span></td>
                                    <td>
                                        <small class="d-block text-muted">From: <?php echo date('M d', strtotime($req['start_date'])); ?></small>
                                        <small class="d-block text-muted">To: <?php echo date('M d', strtotime($req['end_date'])); ?></small>
                                    </td>
                                    <td><span title="<?php echo Security::clean($req['reason']); ?>"><?php echo substr(Security::clean($req['reason']), 0, 30); ?>...</span></td>
                                    <td>
                                        <form action="<?php echo BASE_URL; ?>hr/leave" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $req['id']; ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="<?php echo BASE_URL; ?>hr/leave" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $req['id']; ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pendingRequests)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No pending leave requests.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Staff Personal Requests -->
    <div class="table-card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myRequests as $req): ?>
                    <tr>
                        <td><?php echo Security::clean($req['leave_type_name']); ?></td>
                        <td><?php echo $req['start_date']; ?> to <?php echo $req['end_date']; ?></td>
                        <td>
                            <?php 
                            $badge = [
                                'pending' => 'bg-warning',
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ];
                            ?>
                            <span class="badge <?php echo $badge[$req['status']]; ?>"><?php echo ucfirst($req['status']); ?></span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($req['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
