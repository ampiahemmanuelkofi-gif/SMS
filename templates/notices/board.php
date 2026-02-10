<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-megaphone-fill"></i> Notice Board</h2>
        <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                <i class="bi bi-plus-circle"></i> Create Notice
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <?php if (!empty($notices)): ?>
        <?php foreach ($notices as $notice): ?>
            <div class="col-md-6 mb-4">
                <div class="table-card h-100">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-info text-dark"><?php echo ucfirst($notice['audience']); ?></span>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></small>
                    </div>
                    <h4><?php echo Security::clean($notice['title']); ?></h4>
                    <p class="mt-3"><?php echo nl2br(Security::clean($notice['content'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-journal-x fs-1 mb-3"></i>
            <p>No notices found at this time.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>notices/add" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header"><h5>Publish New Notice</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Audience</label>
                        <select name="audience" class="form-select" required>
                            <option value="all">All</option>
                            <option value="teachers">Teachers</option>
                            <option value="parents">Parents</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Publish Notice</button></div>
            </form>
        </div>
    </div>
</div>
