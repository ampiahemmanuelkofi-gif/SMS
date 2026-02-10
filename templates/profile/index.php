<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-person-bounding-box"></i> My Profile</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="table-card text-center py-4">
            <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 40px;">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <h4><?php echo Security::clean($user['full_name']); ?></h4>
            <span class="badge bg-primary px-3"><?php echo strtoupper(str_replace('_', ' ', $user['role'])); ?></span>
            <hr>
            <div class="text-start px-3">
                <p class="mb-1"><i class="bi bi-envelope text-muted"></i> <?php echo Security::clean($user['email']); ?></p>
                <p class="mb-1"><i class="bi bi-telephone text-muted"></i> <?php echo Security::clean($user['phone'] ?? 'N/A'); ?></p>
                <p class="mb-0"><i class="bi bi-person-badge text-muted"></i> Username: <?php echo Security::clean($user['username']); ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="table-card shadow-sm border-0">
            <h5>Change Password</h5>
            <hr>
            <form action="<?php echo BASE_URL; ?>profile/updatePassword" method="POST" class="mt-3">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                        <small class="text-muted">Minimum 6 characters.</small>
                    </div>
                </div>
                
                <div class="mt-4 pt-2 text-end">
                    <button type="submit" class="btn btn-primary px-4">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
