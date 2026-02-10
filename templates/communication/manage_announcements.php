<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-pencil-square"></i> Manage Announcements</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">Publish New Notice</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>communication/announcements" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Audience</label>
                        <select name="target_audience" class="form-select" required>
                            <option value="all">Everyone</option>
                            <option value="staff">Staff Only</option>
                            <option value="parents">Parents Only</option>
                            <option value="students">Students Only</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Publish Announcement</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white p-3">
                <h5 class="card-title mb-0">Recent Announcements</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $a): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($a['title']); ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?php echo ucfirst($a['target_audience']); ?></span></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($a['created_at'])); ?></small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($announcements)): ?>
                            <tr><td colspan="4" class="text-center py-5">No announcements found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
