<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">User Directory</h6>
        <button class="btn btn-sm btn-primary"><i class="bi bi-person-plus"></i> New User</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-soft-primary text-primary me-3" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <?php echo substr($user['full_name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo $user['full_name']; ?></div>
                                        <small class="text-muted"><?php echo $user['username']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo ucfirst($user['role']); ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($user['is_active'] == 1): ?>
                                    <span class="badge bg-soft-success text-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-soft-danger text-danger">Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                    <form action="<?php echo BASE_URL; ?>admin/users" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <?php if ($user['is_active'] == 1): ?>
                                            <input type="hidden" name="status" value="disabled">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i></button>
                                        <?php else: ?>
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-person-check"></i></button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
