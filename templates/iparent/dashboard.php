<div class="row g-3">
    <!-- Active Child Selector (Mobile Edition) -->
    <div class="col-12">
        <div class="card-mobile-premium p-4 text-white overflow-hidden position-relative mb-2" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="d-flex align-items-center position-relative z-1">
                <div class="avatar-mobile-premium me-3 shadow-sm bg-white bg-opacity-20 border-white border-opacity-30">
                    <i class="bi bi-person-heart fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="x-small opacity-75 fw-bold text-uppercase mb-1 tracking-wider">Active Portfolio</div>
                    <h5 class="mb-0 fw-bold"><?php echo Security::clean($selectedChild['first_name'] . ' ' . $selectedChild['last_name']); ?></h5>
                    <div class="small opacity-75"><?php echo Security::clean($selectedChild['class_name'] . ' | ' . $selectedChild['section_name']); ?></div>
                </div>
                <button class="btn btn-sm btn-glass text-white border-0 shadow-none px-3" data-bs-toggle="modal" data-bs-target="#childModal">
                    <i class="bi bi-arrow-left-right me-1"></i>Switch
                </button>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -40px; right: -40px;"></div>
        </div>
    </div>

    <!-- Quick Stats for Parent -->
    <div class="col-6">
        <div class="card-mobile shadow-sm border-0 bg-white p-3 text-center h-100">
            <div class="text-success mb-1"><i class="bi bi-calendar2-check fs-4"></i></div>
            <h4 class="mb-0 fw-bold text-dark">98%</h4>
            <div class="x-small text-muted fw-bold text-uppercase mt-1">Attendance</div>
        </div>
    </div>
    <div class="col-6">
        <div class="card-mobile shadow-sm border-0 bg-white p-3 text-center h-100">
            <div class="text-primary mb-1"><i class="bi bi-graph-up-arrow fs-4"></i></div>
            <h4 class="mb-0 fw-bold text-dark">A-</h4>
            <div class="x-small text-muted fw-bold text-uppercase mt-1">Avg Grade</div>
        </div>
    </div>

    <!-- News Feed -->
    <div class="col-12 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">School Highlights</h6>
            <a href="#" class="small text-decoration-none">Latest</a>
        </div>
        <div class="card-mobile shadow-sm border-0 bg-white p-0 overflow-hidden">
            <div class="p-3 border-bottom position-relative">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-soft-warning text-warning x-small fw-bold">EVENT</span>
                    <span class="text-muted x-small">2 hours ago</span>
                </div>
                <h6 class="fw-bold text-dark small mb-1">Annual Sports Day 2026</h6>
                <p class="x-small text-muted mb-0 lh-base">Join us this Friday for our annual sports meet at the main stadium...</p>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-soft-info text-info x-small fw-bold">ANNOUNCEMENT</span>
                    <span class="text-muted x-small">Yesterday</span>
                </div>
                <h6 class="fw-bold text-dark small mb-1">Term 2 Exam Schedule</h6>
                <p class="x-small text-muted mb-0 lh-base">The examination schedule has been published in the Documents section.</p>
            </div>
        </div>
    </div>
</div>

<!-- Child Switching Modal (Modernized) -->
<div class="modal fade" id="childModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content border-0 card-mobile-modal shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="modal-title fw-bold text-dark">Switch Student</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="list-group gap-2">
                    <?php foreach ($children as $child): ?>
                    <a href="?switch_child=<?php echo $child['id']; ?>" class="list-group-item list-group-item-action d-flex align-items-center p-3 border rounded-4 <?php echo ($child['id'] == $selectedChild['id']) ? 'border-primary bg-soft-primary' : 'bg-white'; ?> transition-all">
                        <div class="avatar-mobile-small me-3 <?php echo ($child['id'] == $selectedChild['id']) ? 'bg-primary text-white shadow-sm' : 'bg-light text-muted'; ?> rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark small"><?php echo Security::clean($child['first_name'] . ' ' . $child['last_name']); ?></div>
                            <div class="x-small text-muted"><?php echo Security::clean($child['class_name']); ?></div>
                        </div>
                        <?php if ($child['id'] == $selectedChild['id']): ?>
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-mobile-premium {
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(217, 119, 6, 0.2);
    }
    .avatar-mobile-premium {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        font-weight: 600;
        font-size: 0.8rem;
    }
    .btn-glass:hover { background: rgba(255, 255, 255, 0.3); }

    .card-mobile { border-radius: 20px !important; }
    .card-mobile-modal { border-radius: 28px !important; }
    
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.08); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1); }
    
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .transition-all { transition: all 0.2s ease; }
    .z-1 { z-index: 1; }
</style>
