<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-broadcast-pin"></i> Bulk Notifications</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">Send New Alert</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>communication/bulk" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Communication Type</label>
                        <select name="type" class="form-select" required>
                            <option value="email">Email Notification</option>
                            <option value="sms">SMS Message</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Audience</label>
                        <select name="target_audience" class="form-select" required>
                            <option value="all_parents">All Parents</option>
                            <option value="all_staff">All Staff</option>
                            <option value="class_parents">Specific Class Parents</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message Template</label>
                        <select name="template_id" class="form-select" onchange="loadTemplate(this.value)">
                            <option value="">Select a template (Optional)</option>
                            <?php foreach ($templates as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo Security::clean($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject (For Email)</label>
                        <input type="text" name="subject" id="comm_subject" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message Content</label>
                        <textarea name="content" id="comm_content" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Send Notification</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white p-3">
                <h5 class="card-title mb-0">Delivery Logs</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo strtoupper($log['type']); ?>
                                    </span>
                                </td>
                                <td><small><?php echo Security::clean($log['recipient']); ?></small></td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i> Sent
                                    </span>
                                </td>
                                <td><small class="text-muted"><?php echo date('M d, H:i', strtotime($log['sent_at'])); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="4" class="text-center py-5">No logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function loadTemplate(id) {
    if (!id) return;
    // For demo, we just populate static data based on ID
    // In a real app, this would be an AJAX call
    const templates = <?php echo json_encode($templates); ?>;
    const template = templates.find(t => t.id == id);
    if (template) {
        document.getElementById('comm_subject').value = template.subject;
        document.getElementById('comm_content').value = template.body;
    }
}
</script>
