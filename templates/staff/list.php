<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-person-badge-fill"></i> Staff Management</h2>
        <a href="<?php echo BASE_URL; ?>staff/add" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Add Staff Member
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $member): ?>
                    <tr>
                        <td>
                            <div class="user-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                                <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                            </div>
                        </td>
                        <td><strong><?php echo Security::clean($member['full_name']); ?></strong></td>
                        <td><?php echo Security::clean($member['username']); ?></td>
                        <td>
                            <?php
                            $roleMap = [
                                'super_admin' => 'danger',
                                'admin' => 'primary',
                                'teacher' => 'success',
                                'accountant' => 'info'
                            ];
                            $badge = isset($roleMap[$member['role']]) ? $roleMap[$member['role']] : 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $badge; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $member['role'])); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $member['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="bi bi-telephone"></i> <?php echo Security::clean($member['phone']); ?><br>
                                <i class="bi bi-envelope"></i> <?php echo Security::clean($member['email']); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>staff/edit/<?php echo $member['id']; ?>" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
