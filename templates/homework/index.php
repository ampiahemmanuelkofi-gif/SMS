<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title"><i class="bi bi-journal-text"></i> Homework & Assignments</h2>
            <p class="text-muted">Track assignments posted by teachers across different subjects.</p>
        </div>
        <?php if (Auth::getRole() === 'teacher'): ?>
            <a href="<?php echo BASE_URL; ?>homework/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Post New Homework
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($homework)): ?>
        <div class="col-12">
            <div class="table-card py-5 text-center text-muted">
                <i class="bi bi-journal-x fs-1 mb-3"></i>
                <p>No homework or assignments have been posted yet.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($homework as $h): ?>
            <div class="col-md-6 col-lg-4">
                <div class="table-card h-100 shadow-sm border-0 border-top border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-light text-primary border border-primary"><?php echo Security::clean($h['subject_name']); ?></span>
                        <small class="text-muted"><i class="bi bi-calendar-event"></i> Due: <?php echo date('d M Y', strtotime($h['deadline'])); ?></small>
                    </div>
                    <h5 class="fw-bold mt-2"><?php echo Security::clean($h['title']); ?></h5>
                    <p class="text-muted small mb-3">
                        <?php echo Security::clean($h['class_name'] . ' ' . $h['section_name']); ?>
                        <?php if (isset($h['student_name'])): ?>
                            | For: <strong><?php echo Security::clean($h['student_name']); ?></strong>
                        <?php endif; ?>
                    </p>
                    <div class="p-2 bg-light rounded mb-3" style="font-size: 0.9rem; min-height: 60px;">
                        <?php echo nl2br(Security::clean($h['description'])); ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                        <small class="text-muted">By: <?php echo Security::clean($h['teacher_name'] ?? 'Assigned Teacher'); ?></small>
                        <?php if (Auth::getRole() === 'teacher' || in_array(Auth::getRole(), ['super_admin', 'admin'])): ?>
                            <a href="<?php echo BASE_URL; ?>homework/delete/<?php echo $h['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this assignment?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
