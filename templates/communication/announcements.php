<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-megaphone"></i> School Announcements</h2>
        <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
            <a href="<?php echo BASE_URL; ?>communication/announcements" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Manage News
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($announcements as $a): ?>
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary-subtle text-primary">
                            <?php echo strtoupper(str_replace('_', ' ', $a['target_audience'])); ?>
                        </span>
                        <small class="text-muted"><i class="bi bi-clock"></i> <?php echo date('M d, Y', strtotime($a['created_at'])); ?></small>
                    </div>
                    <h4 class="card-title fw-bold mb-3"><?php echo Security::clean($a['title']); ?></h4>
                    <div class="card-text text-secondary mb-4" style="line-height: 1.6;">
                        <?php echo nl2br(Security::clean($a['content'])); ?>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0 small fw-bold text-dark"><?php echo Security::clean($a['author_name']); ?></p>
                            <p class="mb-0 small text-muted">School Administration</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($announcements)): ?>
        <div class="col-md-8 mx-auto text-center py-5">
            <i class="bi bi-bell-slash fs-1 text-muted opacity-50"></i>
            <p class="mt-3 text-muted">No recent announcements to display.</p>
        </div>
    <?php endif; ?>
</div>
