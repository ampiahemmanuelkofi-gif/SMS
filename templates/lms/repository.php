<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-folder2-open"></i> Content Repository</h2>
        <?php if (Auth::hasRole(['teacher', 'admin'])): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContentModal">
                <i class="bi bi-plus-lg"></i> Add New Content
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="table-card p-3">
            <form action="<?php echo BASE_URL; ?>lms/repository" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Filter by Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $filters['subject_id'] == $subject['id'] ? 'selected' : ''; ?>><?php echo $subject['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($content)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="mt-2">No learning resources found for the selected filter.</p>
        </div>
    <?php else: ?>
        <?php foreach ($content as $item): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3">
                        <span class="badge bg-soft-info text-info mb-2"><?php echo $item['subject_name']; ?></span>
                        <h6 class="card-title fw-bold"><?php echo Security::clean($item['title']); ?></h6>
                    </div>
                    <div class="card-body">
                        <?php if ($item['content_type'] == 'video_link'): ?>
                            <div class="ratio ratio-16x9 bg-dark rounded mb-3">
                                <div class="d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-play-fill fs-1"></i>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-light rounded text-center mb-3">
                                <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                            </div>
                        <?php endif; ?>
                        <p class="small text-muted mb-0">Added on <?php echo date('M d, Y', strtotime($item['created_at'])); ?></p>
                        <p class="small text-muted">By <?php echo $item['creator_name']; ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <?php if ($item['content_type'] == 'video_link'): ?>
                            <a href="<?php echo $item['content_value']; ?>" target="_blank" class="btn btn-danger w-100">
                                <i class="bi bi-youtube"></i> Watch on YouTube
                            </a>
                        <?php else: ?>
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> View Resource
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>lms/add_content" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Learning Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="content_type" class="form-select" required>
                        <option value="file">File Resource (PDF/Doc)</option>
                        <option value="video_link">Video Lesson (Link)</option>
                        <option value="text">Notes/Text</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content Value / Link</label>
                    <textarea name="content_value" class="form-control" rows="3" placeholder="Paste YouTube link or file identifier"></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_public" class="form-check-input" id="is_public" checked>
                    <label class="form-check-label" for="is_public">Make visible to students</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Post to Repository</button>
            </div>
        </form>
    </div>
</div>
