<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-book-half"></i> Learning Management System</h2>
        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>lms/repository" class="btn btn-outline-primary">
                <i class="bi bi-folder2-open"></i> Repository
            </a>
            <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
                <a href="<?php echo BASE_URL; ?>lms/settings" class="btn btn-outline-secondary">
                    <i class="bi bi-gear-fill"></i> Integration Settings
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Active Platforms -->
    <div class="col-md-12">
        <div class="table-card p-4">
            <h5 class="mb-3 border-bottom pb-2">Integrated Platforms</h5>
            <div class="row g-3">
                <?php if (empty($platforms)): ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No platforms configured. <a href="<?php echo BASE_URL; ?>lms/settings">Configure now</a></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($platforms as $platform): ?>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="platform-icon me-3">
                                            <?php if ($platform['platform_key'] == 'google'): ?>
                                                <i class="bi bi-google text-primary fs-3"></i>
                                            <?php elseif ($platform['platform_key'] == 'microsoft'): ?>
                                                <i class="bi bi-microsoft text-info fs-3"></i>
                                            <?php else: ?>
                                                <i class="bi bi-mortarboard text-secondary fs-3"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo $platform['name']; ?></h6>
                                            <span class="badge bg-success small">Connected</span>
                                        </div>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>lms/sync_assignments/<?php echo $platform['platform_key']; ?>" class="btn btn-link text-primary p-0 me-2" title="Sync Assignments"><i class="bi bi-arrow-repeat"></i></a>
                                        <a href="<?php echo BASE_URL; ?>lms/sync_grades/<?php echo $platform['platform_key']; ?>" class="btn btn-link text-success p-0" title="Sync Grades"><i class="bi bi-cloud-upload"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Stats -->
    <div class="col-md-8">
        <div class="table-card p-4">
            <h5 class="mb-3 border-bottom pb-2">Recent Learning Content</h5>
            <?php if (empty($recentContent)): ?>
                <p class="text-center text-muted py-5">No content found in the repository.</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentContent as $item): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><?php echo Security::clean($item['title']); ?></h6>
                                <small class="text-muted"><?php echo $item['subject_name']; ?> | <?php echo ucfirst($item['content_type']); ?></small>
                            </div>
                            <?php if ($item['content_type'] == 'video_link'): ?>
                                <a href="<?php echo $item['content_value']; ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i class="bi bi-play-btn"></i> Watch</a>
                            <?php else: ?>
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-card p-4 h-100">
            <h5 class="mb-3 border-bottom pb-2">LMS Quick Stats</h5>
            <div class="text-center py-3">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h3 class="mb-0 text-primary">12</h3>
                            <small class="text-muted">Courses</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h3 class="mb-0 text-success"><?php echo count($quizzes); ?></h3>
                            <small class="text-muted">Quizzes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h3 class="mb-0 text-info">45</h3>
                            <small class="text-muted">Files</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h3 class="mb-0 text-warning">8</h3>
                            <small class="text-muted">Videos</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h6>Upcoming Deadlines</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-clock text-danger"></i> Math Assignment (Classroom) - Today</li>
                    <li><i class="bi bi-clock text-warning"></i> Science Quiz - Tomorrow</li>
                </ul>
            </div>
        </div>
    </div>
</div>
