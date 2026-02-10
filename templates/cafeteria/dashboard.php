<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-egg-fried text-warning"></i> Cafeteria Dashboard</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-day me-2"></i>Today's Menu - <?php echo date('l, M d, Y'); ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($today_menu as $m): ?>
                        <div class="list-group-item p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-info-subtle text-info mb-2"><?php echo $m['type_name']; ?> (<?php echo date('H:i', strtotime($m['start_time'])); ?>)</span>
                                    <h4 class="fw-bold mb-1"><?php echo Security::clean($m['menu_item']); ?></h4>
                                    <p class="text-muted mb-0"><?php echo Security::clean($m['description']); ?></p>
                                </div>
                                <a href="<?php echo BASE_URL; ?>cafeteria/attendance?menu_id=<?php echo $m['id']; ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                    Meal Check-off <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($today_menu)): ?>
                        <div class="p-5 text-center">
                            <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No menu items published for today.</h5>
                            <a href="<?php echo BASE_URL; ?>cafeteria/menus" class="btn btn-outline-primary mt-2">Go to Planning</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 text-center">
                <div class="bg-warning bg-opacity-10 p-4 rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-shield-exclamation text-warning fs-1"></i>
                </div>
                <h5 class="fw-bold">Dietary Registry</h5>
                <p class="small text-muted">Manage food allergies and specific student requirements.</p>
                <a href="<?php echo BASE_URL; ?>cafeteria/restrictions" class="btn btn-dark w-100">View Registry</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-gradient bg-success text-white">
            <div class="card-body p-4">
                <h6 class="text-uppercase opacity-75 small fw-bold">Active Meal Service</h6>
                <div class="d-flex align-items-center mt-3">
                    <div class="spinner-grow spinner-grow-sm text-white me-2" role="status"></div>
                    <h5 class="mb-0">Lunch Service On</h5>
                </div>
                <hr class="bg-white opacity-25">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Expected Attendance</span>
                    <h4 class="fw-bold mb-0">120</h4>
                </div>
            </div>
        </div>
    </div>
</div>
