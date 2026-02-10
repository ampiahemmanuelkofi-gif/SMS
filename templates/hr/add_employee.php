<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-person-plus"></i> Add New Employee</h2>
        <p class="text-muted">Register a new staff member and create their system account.</p>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>hr/add" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
    
    <div class="row">
        <!-- Account Information -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-person-lock"></i> Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="teacher">Teacher</option>
                                <option value="accountant">Accountant</option>
                                <option value="librarian">Librarian</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success"><i class="bi bi-briefcase"></i> Employment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee ID/Code</label>
                            <input type="text" name="employee_id" class="form-control" placeholder="e.g. EMP001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Tutor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Base Salary (GH₵)</label>
                        <input type="number" name="base_salary" class="form-control" step="0.01" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="account_number" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SSNIT Number</label>
                        <input type="text" name="ssnit_number" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-end mb-5">
            <a href="<?php echo BASE_URL; ?>hr/directory" class="btn btn-outline-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary px-5">Save Employee Profile</button>
        </div>
    </div>
</form>
