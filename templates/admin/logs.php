<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">System Audit Trail</h6>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-secondary">Download CSV</button>
            <button class="btn btn-sm btn-outline-danger">Clear Logs</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Action Performed</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th class="pe-4">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="ps-4"><code class="small text-muted">#<?php echo $log['id']; ?></code></td>
                            <td>
                                <span class="badge bg-soft-secondary text-dark border-0"><?php echo strtoupper($log['action']); ?></span>
                                <div class="small mt-1"><?php echo $log['detail']; ?></div>
                            </td>
                            <td>
                                <div class="fw-bold small"><?php echo $log['full_name']; ?></div>
                                <div class="text-muted x-small">@<?php echo $log['username']; ?></div>
                            </td>
                            <td><code class="small"><?php echo $log['ip_address']; ?></code></td>
                            <td class="pe-4">
                                <div class="small fw-bold"><?php echo date('Y-m-d', strtotime($log['created_at'])); ?></div>
                                <div class="text-muted x-small"><?php echo date('H:i:s', strtotime($log['created_at'])); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 11px; }
    .bg-soft-secondary { background-color: #f1f3f5; }
</style>
