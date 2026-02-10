<?php
$currency = setting('currency_symbol', 'GH₵');
?>

<div class="mb-4">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1 class="page-title mb-1"><i class="bi bi-person-hearts me-2 text-primary"></i>Parent Portal</h1>
            <p class="text-muted mb-0">Welcome back, <strong><?php echo Auth::getFullName(); ?></strong>! Explore your children's progress and school updates below.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php if (!empty($children)): ?>
        <?php foreach ($children as $child): ?>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100 student-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="student-avatar me-3">
                                <?php echo strtoupper(substr($child['first_name'], 0, 1) . substr($child['last_name'], 0, 1)); ?>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-0 text-dark"><?php echo Security::clean($child['first_name'] . ' ' . $child['last_name']); ?></h5>
                                <div class="text-muted small">
                                    <span class="badge bg-soft-primary text-primary me-2"><?php echo Security::clean($child['student_id']); ?></span>
                                    <?php echo Security::clean($child['class_name'] . ' ' . $child['section_name']); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="<?php echo BASE_URL; ?>parent/marks/<?php echo $child['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye-fill me-1"></i>Report Card
                                </a>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 border border-white text-center">
                                    <div class="text-muted small text-uppercase fw-bold mb-2">Attendance</div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="circular-progress" style="--value: <?php echo $child['attendance_percentage']; ?>;">
                                            <span class="fw-bold fs-5"><?php echo $child['attendance_percentage']; ?>%</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 small text-primary fw-medium">Term Average</div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 border border-white h-100 text-center d-flex flex-column justify-content-center">
                                    <div class="text-muted small text-uppercase fw-bold mb-2">Fee Balance</div>
                                    <h4 class="fw-bold <?php echo $child['fee_balance'] > 0 ? 'text-danger' : 'text-success'; ?> mb-0">
                                        <?php echo $currency . ' ' . number_format($child['fee_balance'], 2); ?>
                                    </h4>
                                    <p class="mb-0 small text-muted mt-2">Due this term</p>
                                    <?php if ($child['fee_balance'] > 0): ?>
                                        <a href="<?php echo BASE_URL; ?>parent/payments" class="btn btn-sm btn-link text-danger text-decoration-none fw-bold mt-1">Pay Now</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top grid gap-2 d-flex">
                            <a href="<?php echo BASE_URL; ?>parent/attendance/<?php echo $child['id']; ?>" class="btn btn-soft-info flex-fill">
                                <i class="bi bi-calendar-check me-2"></i>Attendance
                            </a>
                            <a href="<?php echo BASE_URL; ?>parent/timetable/<?php echo $child['id']; ?>" class="btn btn-soft-secondary flex-fill">
                                <i class="bi bi-calendar3 me-2"></i>Timetable
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-soft-warning">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning d-block mb-3"></i>
                    <h5 class="fw-bold">No Children Linked</h5>
                    <p class="text-muted mb-0">We couldn't find any children linked to your account. Please contact the school's IT support or registrar to update your profile.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Notices Section -->
    <div class="col-xl-12 mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone-fill me-2 text-warning"></i>School News & Notices</h6>
                <a href="#" class="text-decoration-none small">Archive</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($notices)): ?>
                        <?php foreach ($notices as $notice): ?>
                            <div class="list-group-item px-4 py-3 border-0 border-start border-4 border-warning mb-2 mx-3 rounded-3 bg-light bg-opacity-50">
                                <div class="d-flex w-100 justify-content-between mb-2">
                                    <h6 class="mb-0 fw-bold text-dark"><?php echo Security::clean($notice['title']); ?></h6>
                                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></small>
                                </div>
                                <p class="mb-0 text-muted small lh-base"><?php echo Security::clean($notice['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
                            No recent notices available
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .student-card { border-radius: 20px !important; overflow: hidden; }
    .student-avatar {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    .btn-soft-info { background-color: rgba(76, 201, 240, 0.1); color: #0ea5e9; border: none; font-weight: 600; font-size: 0.85rem; }
    .btn-soft-info:hover { background-color: #0ea5e9; color: white; }
    .btn-soft-secondary { background-color: #f1f5f9; color: #475569; border: none; font-weight: 600; font-size: 0.85rem; }
    .btn-soft-secondary:hover { background-color: #e2e8f0; color: #1e293b; }

    /* Circular Progress Mock */
    .circular-progress {
        --size: 80px;
        --thickness: 6px;
        width: var(--size);
        height: var(--size);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(#4361ee calc(var(--value) * 1%), #e2e8f0 0);
    }
</style>
