<div class="row g-3">
    <!-- Teacher Profile Header (Mobile Edition) -->
    <div class="col-12">
        <div class="card-mobile-premium p-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);">
            <div class="d-flex align-items-center position-relative z-1">
                <div class="avatar-mobile-premium me-3 shadow-sm bg-white bg-opacity-20 border-white border-opacity-30">
                    <i class="bi bi-person-workspace fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Hi, <?php echo Auth::getFullName() ?: $_SESSION['username']; ?></h5>
                    <div class="small opacity-75">
                        <i class="bi bi-clock me-1 text-white"></i>Next: Physics (10:30 AM)
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -30px; right: -30px;"></div>
        </div>
    </div>

    <!-- Activity Stats -->
    <div class="col-6">
        <div class="card-mobile shadow-sm border-0 bg-white p-3 text-center h-100">
            <div class="text-primary mb-1"><i class="bi bi-calendar-check fs-4"></i></div>
            <h4 class="mb-0 fw-bold text-dark">95%</h4>
            <div class="x-small text-muted fw-bold text-uppercase mt-1">Attendance</div>
        </div>
    </div>
    <div class="col-6">
        <div class="card-mobile shadow-sm border-0 bg-white p-3 text-center h-100">
            <div class="text-success mb-1"><i class="bi bi-journal-check fs-4"></i></div>
            <h4 class="mb-0 fw-bold text-dark">12</h4>
            <div class="x-small text-muted fw-bold text-uppercase mt-1">Assignments</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">Quick Actions</h6>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <a href="<?php echo BASE_URL; ?>iteacher/attendance" class="portal-action-pill bg-soft-primary text-decoration-none">
                    <div class="action-icon bg-white text-primary shadow-sm">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <span class="text-dark small fw-bold">Attendance</span>
                </a>
            </div>
            <div class="col-6">
                <a href="<?php echo BASE_URL; ?>iteacher/behavior" class="portal-action-pill bg-soft-warning text-decoration-none">
                    <div class="action-icon bg-white text-warning shadow-sm">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <span class="text-dark small fw-bold">Incident</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="col-12 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">Today's Schedule</h6>
            <a href="<?php echo BASE_URL; ?>iteacher/timetable" class="small text-decoration-none">View All</a>
        </div>
        <div class="card-mobile shadow-sm border-0 bg-white p-0 overflow-hidden">
            <div class="list-group list-group-flush">
                <div class="list-group-item px-3 py-3 d-flex justify-content-between align-items-center border-0 bg-soft-success bg-opacity-10 mb-1 mx-2 mt-2 rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-2 me-3">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Mathematics</div>
                            <div class="x-small text-muted">Grade 10A • 08:30 AM</div>
                        </div>
                    </div>
                    <span class="badge bg-soft-success text-success x-small fw-bold">Ended</span>
                </div>
                
                <div class="list-group-item px-3 py-3 d-flex justify-content-between align-items-center border-0 bg-soft-primary bg-opacity-10 mb-2 mx-2 rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-2 me-3">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Physics</div>
                            <div class="x-small text-muted">Grade 11B • 10:30 AM</div>
                        </div>
                    </div>
                    <span class="badge bg-primary x-small fw-bold">Now</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-mobile-premium {
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
    }
    .avatar-mobile-premium {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid;
    }
    .card-mobile { border-radius: 20px !important; }
    
    .portal-action-pill {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 16px;
        gap: 12px;
        transition: transform 0.2s;
    }
    .portal-action-pill:active { transform: scale(0.95); }
    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .bg-soft-primary { background-color: rgba(37, 99, 235, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1); }
    
    .x-small { font-size: 0.7rem; }
    .z-1 { z-index: 1; }
</style>
