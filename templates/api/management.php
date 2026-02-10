<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-diagram-3"></i> API & Integrations</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addKeyModal">
            <i class="bi bi-key me-1"></i> Generate New API Key
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- API Keys -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Active API Keys</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client Name</th>
                            <th>API Key</th>
                            <th>Status</th>
                            <th>Last Used</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keys as $k): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($k['client_name']); ?></strong></td>
                                <td><code><?php echo substr($k['api_key'], 0, 8); ?>...</code></td>
                                <td>
                                    <span class="badge <?php echo $k['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $k['is_active'] ? 'Active' : 'Revoked'; ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?php echo $k['last_used_at'] ?: 'Never'; ?></small></td>
                                <td>
                                    <?php if ($k['is_active']): ?>
                                        <a href="<?php echo BASE_URL; ?>integrations/revokeKey/<?php echo $k['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to revoke this key?')">Revoke</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Recent API Activity</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Endpoint</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td><small><?php echo Security::clean($l['client_name']); ?></small></td>
                                <td><code><?php echo Security::clean($l['endpoint']); ?></code></td>
                                <td><span class="badge bg-light text-dark"><?php echo $l['method']; ?></span></td>
                                <td><span class="badge <?php echo $l['response_code'] < 400 ? 'bg-info' : 'bg-warning'; ?>"><?php echo $l['response_code']; ?></span></td>
                                <td><small class="text-muted"><?php echo date('H:i:s', strtotime($l['requested_at'])); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-dark text-white mb-4">
            <div class="card-body">
                <h5 class="fw-bold"><i class="bi bi-book me-2"></i> Developer Portal</h5>
                <p class="small opacity-75">Access our API documentation, library SDKs, and sandbox tools to build your custom integrations.</p>
                <div class="d-grid">
                    <a href="<?php echo BASE_URL; ?>integrations/docs" class="btn btn-primary">View API Documentation</a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Webhook Configuration</h6>
                <p class="small text-muted">Configure endpoints to receive real-time notifications for system events like student registration or result publication.</p>
                <button class="btn btn-outline-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#setupWebhookModal">Configure Webhooks</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Key Modal -->
<div class="modal fade" id="addKeyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate API Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>integrations/generateKey" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="client_name" class="form-control" placeholder="e.g. Parent App, Accounting System" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions Scope</label>
                        <select name="permissions" class="form-select">
                            <option value="all">Full Access (Read/Write)</option>
                            <option value="read">Read Only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Key</button>
                </div>
            </form>
        </div>
    </div>
</div>
