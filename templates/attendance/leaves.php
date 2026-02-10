
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-file-earmark-medical"></i> Leave Management</h2>
    </div>
</div>

<div class="row">
    <!-- Leave Request Form -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">New Leave Request</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>attendance/saveLeave" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="number" name="student_id" class="form-control" required placeholder="Enter ID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Pending Leaves List -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pending Requests</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Dates</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingLeaves as $leave): ?>
                            <tr>
                                <td>
                                    <?php echo $leave['first_name'] . ' ' . $leave['last_name']; ?>
                                    <small class="d-block text-muted"><?php echo $leave['class_name']; ?></small>
                                </td>
                                <td>
                                    <?php echo $leave['start_date']; ?> to<br>
                                    <?php echo $leave['end_date']; ?>
                                </td>
                                <td><?php echo $leave['reason']; ?></td>
                                <td>
                                    <form action="<?php echo BASE_URL; ?>attendance/saveLeave" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                                        <button type="submit" name="approve_leave" class="btn btn-success btn-sm">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="submit" name="reject_leave" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pendingLeaves)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No pending leave requests.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
