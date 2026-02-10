<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-person-hearts"></i> My Children</h2>
        <p class="text-muted">Quick access to academic and financial records for your children.</p>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($children)): ?>
        <div class="col-12">
            <div class="alert alert-warning">
                No children found linked to your account. Please ensure your email address matches the one provided during admission.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($children as $c): ?>
            <div class="col-md-6 col-lg-4">
                <div class="table-card shadow-sm border-0 h-100 transition-hover">
                    <div class="text-center mb-3">
                        <img src="<?php echo $c['photo'] ? BASE_URL . $c['photo'] : BASE_URL . 'assets/images/placeholder.png'; ?>" 
                             class="rounded-circle shadow-sm border" width="100" height="100" style="object-fit: cover;">
                        <h4 class="mt-3 mb-1"><?php echo Security::clean($c['first_name'] . ' ' . $c['last_name']); ?></h4>
                        <span class="text-muted small"><?php echo $c['student_id']; ?></span>
                    </div>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between p-2">
                            <span>Class</span>
                            <span class="fw-bold"><?php echo Security::clean($c['class_name'] . ' ' . $c['section_name']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-2">
                            <span>Status</span>
                            <span class="badge bg-success"><?php echo ucfirst($c['status']); ?></span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>parent/childDetails/<?php echo $c['id']; ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> View Profile
                        </a>
                        <a href="<?php echo BASE_URL; ?>assessments/reportCard/<?php echo $c['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Terminal Report
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.transition-hover:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}
</style>
