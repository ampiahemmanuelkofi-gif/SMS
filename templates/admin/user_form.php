<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-sm btn-light me-3"><i class="bi bi-arrow-left"></i></a>
                    <h5 class="mb-0 fw-bold"><?php echo $action; ?> User</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>admin/<?php echo ($action === 'Add') ? 'addUser' : 'editUser/' . $user['id']; ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo $user['full_name'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $user['username'] ?? ''; ?>" <?php echo ($action === 'Edit') ? 'readonly' : 'required'; ?>>
                            <?php if ($action === 'Edit'): ?>
                                <small class="text-muted">Username cannot be changed.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user['email'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="super_admin" <?php echo (isset($user['role']) && $user['role'] === 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                                <option value="admin" <?php echo (isset($user['role']) && $user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="accountant" <?php echo (isset($user['role']) && $user['role'] === 'accountant') ? 'selected' : ''; ?>>Accountant</option>
                                <option value="teacher" <?php echo (isset($user['role']) && $user['role'] === 'teacher') ? 'selected' : ''; ?>>Teacher</option>
                                <option value="staff" <?php echo (isset($user['role']) && $user['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                                <option value="hr" <?php echo (isset($user['role']) && $user['role'] === 'hr') ? 'selected' : ''; ?>>HR Manager</option>
                                <option value="librarian" <?php echo (isset($user['role']) && $user['role'] === 'librarian') ? 'selected' : ''; ?>>Librarian</option>
                                <option value="student" <?php echo (isset($user['role']) && $user['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="lab_assistant" <?php echo (isset($user['role']) && $user['role'] === 'lab_assistant') ? 'selected' : ''; ?>>Lab Assistant</option>
                                <option value="admissions_officer" <?php echo (isset($user['role']) && $user['role'] === 'admissions_officer') ? 'selected' : ''; ?>>Admissions Officer</option>
                                <option value="inventory_manager" <?php echo (isset($user['role']) && $user['role'] === 'inventory_manager') ? 'selected' : ''; ?>>Inventory Manager</option>
                                <option value="receptionist" <?php echo (isset($user['role']) && $user['role'] === 'receptionist') ? 'selected' : ''; ?>>Receptionist</option>
                                <option value="hostel_warden" <?php echo (isset($user['role']) && $user['role'] === 'hostel_warden') ? 'selected' : ''; ?>>Hostel Warden/Matron</option>
                                <option value="nurse" <?php echo (isset($user['role']) && $user['role'] === 'nurse') ? 'selected' : ''; ?>>School Nurse/Health Officer</option>
                                <option value="cafeteria_manager" <?php echo (isset($user['role']) && $user['role'] === 'cafeteria_manager') ? 'selected' : ''; ?>>Cafeteria Manager</option>
                                <option value="transport_manager" <?php echo (isset($user['role']) && $user['role'] === 'transport_manager') ? 'selected' : ''; ?>>Transport Manager</option>
                                <option value="alumni" <?php echo (isset($user['role']) && $user['role'] === 'alumni') ? 'selected' : ''; ?>>Alumni</option>
                                <option value="security_officer" <?php echo (isset($user['role']) && $user['role'] === 'security_officer') ? 'selected' : ''; ?>>Security Officer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?php echo ($action === 'Add') ? 'Password' : 'New Password (leave blank to keep current)'; ?></label>
                            <input type="password" name="password" class="form-control" <?php echo ($action === 'Add') ? 'required' : ''; ?>>
                        </div>

                        <?php if ($action === 'Edit' && isset($modules)): ?>
                        <div class="col-12 mt-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-shield-lock me-2"></i>Module Access Overrides</h6>
                                    <div class="form-check form-switch ms-auto">
                                        <input class="form-check-input" type="checkbox" name="reset_permissions" value="1" id="resetPerms">
                                        <label class="form-check-label x-small text-muted" for="resetPerms">Reset to Role Defaults</label>
                                    </div>
                                </div>
                                <p class="x-small text-muted mb-3">Explicitly grant or revoke access to specific modules for this user. Overrides will take precedence over role permissions.</p>
                                
                                <div class="row g-3">
                                    <?php foreach ($modules as $key => $name): ?>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="user_permissions[<?php echo $key; ?>]" 
                                                       id="perm_<?php echo $key; ?>" 
                                                       value="1"
                                                       <?php echo (isset($userPermissions[$key]) && $userPermissions[$key] == 1) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small" for="perm_<?php echo $key; ?>">
                                                    <?php echo $name; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><?php echo $action; ?> User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
