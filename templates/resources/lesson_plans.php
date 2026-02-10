<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">Lesson Plans</h2>
        <?php if (Auth::hasRole(['teacher', 'admin'])): ?>
            <button type="button" class="btn btn-primary" disabled title="Upload feature coming soon">
                <i class="bi bi-upload"></i> Upload Plan
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card">
            <?php if (empty($plans)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-check display-4 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No lesson plans found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Week</th>
                                <th>Teacher</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plans as $plan): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($plan['topic']); ?></strong></td>
                                    <td><?php echo Security::clean($plan['subject_name']); ?></td>
                                    <td><?php echo Security::clean($plan['class_name']); ?></td>
                                    <td><?php echo $plan['week_number']; ?></td>
                                    <td><?php echo Security::clean($plan['teacher_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $plan['status'] === 'approved' ? 'success' : ($plan['status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($plan['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
