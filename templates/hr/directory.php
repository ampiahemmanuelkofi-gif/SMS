<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-person-lines-fill"></i> Employee Directory</h2>
        <a href="<?php echo BASE_URL; ?>hr/add" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Add Employee
        </a>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>hr/directory" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active Employees</option>
                    <option value="on_leave" <?php echo $status == 'on_leave' ? 'selected' : ''; ?>>On Leave</option>
                    <option value="terminated" <?php echo $status == 'terminated' ? 'selected' : ''; ?>>Terminated</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Staff Code</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Joining Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><code><?php echo Security::clean($emp['employee_id']); ?></code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <?php echo strtoupper(substr($emp['full_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <strong><?php echo Security::clean($emp['full_name']); ?></strong><br>
                                    <small class="text-muted"><?php echo Security::clean($emp['user_email']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary"><?php echo Security::clean($emp['department_name']); ?></span></td>
                        <td><?php echo Security::clean($emp['designation']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($emp['joining_date'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                    <tr><td colspan="6" class="text-center py-4">No employees found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
