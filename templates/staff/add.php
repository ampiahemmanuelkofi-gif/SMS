<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>staff">Staff</a></li>
                <li class="breadcrumb-item active">Add Staff</li>
            </ol>
        </nav>
        <h2 class="page-title"><i class="bi bi-person-plus-fill"></i> Add New Staff Member</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="table-card">
            <form action="<?php echo BASE_URL; ?>staff/add" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. John Doe" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="teacher">Teacher</option>
                            <option value="accountant">Accountant</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Unique username" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+233 ...">
                    </div>
                </div>
                
                <div class="mt-4 border-top pt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle"></i> Create Account
                    </button>
                    <a href="<?php echo BASE_URL; ?>staff" class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
