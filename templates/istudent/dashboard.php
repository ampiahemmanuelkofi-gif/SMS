<div class="row g-3">
    <!-- Student Profile Header (Mobile Edition) -->
    <div class="col-12">
        <div class="card-mobile-premium p-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
            <div class="d-flex align-items-center position-relative z-1">
                <div class="avatar-mobile-premium me-3 shadow-sm">
                    <?php echo strtoupper(substr($student['first_name'], 0, 1)); ?>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold"><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></h5>
                    <div class="small opacity-75">
                        <i class="bi bi-mortarboard me-1"></i><?php echo Security::clean($student['class_name']); ?> | ID: <?php echo Security::clean($student['student_id']); ?>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -50px; right: -50px;"></div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="col-4">
        <a href="<?php echo BASE_URL; ?>istudent/timetable" class="text-decoration-none portal-icon-btn">
            <div class="icon-box bg-soft-primary">
                <i class="bi bi-calendar3 text-primary"></i>
            </div>
            <span class="text-dark small fw-bold">Schedule</span>
        </a>
    </div>
    <div class="col-4">
        <a href="<?php echo BASE_URL; ?>istudent/assignments" class="text-decoration-none portal-icon-btn">
            <div class="icon-box bg-soft-success">
                <i class="bi bi-journal-text text-success"></i>
            </div>
            <span class="text-dark small fw-bold">Homework</span>
        </a>
    </div>
    <div class="col-4">
        <a href="#" class="text-decoration-none portal-icon-btn">
            <div class="icon-box bg-soft-warning">
                <i class="bi bi-award text-warning"></i>
            </div>
            <span class="text-dark small fw-bold">Results</span>
        </a>
    </div>

    <!-- Live Status / Current Class -->
    <div class="col-12">
        <div class="card-mobile shadow-sm border-0 bg-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-soft-danger text-danger px-2 text-uppercase x-small fw-bold">Live Status</span>
                <span class="text-muted small">Period 4 • 10:30 AM</span>
            </div>
            <div class="d-flex align-items-center">
                <div class="subject-icon me-3 bg-soft-info text-info">
                    <i class="bi bi-calculator"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Mathematics</h6>
                    <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>Lab C • Mr. Smith</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments Tracker -->
    <div class="col-12 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark">Pending Tasks</h6>
            <a href="#" class="small text-decoration-none">See All</a>
        </div>
        <div class="card-mobile shadow-sm border-0 bg-white p-0">
            <div class="p-3 border-bottom d-flex align-items-center">
                <div class="task-checkbox me-3">
                    <i class="bi bi-circle text-muted"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold text-dark small">Physics: Wave Motion Exercises</h6>
                    <div class="x-small text-danger fw-bold mt-1"><i class="bi bi-clock me-1"></i>Due Today, 4:00 PM</div>
                </div>
            </div>
            <div class="p-3 d-flex align-items-center">
                <div class="task-checkbox me-3">
                    <i class="bi bi-circle text-muted"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold text-dark small">History: Industrial Revolution Essay</h6>
                    <div class="x-small text-muted mt-1"><i class="bi bi-calendar-event me-1"></i>Due Tomorrow</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-mobile-premium {
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }
    .avatar-mobile-premium {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
    }
    .portal-icon-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: transform 0.2s;
    }
    .icon-box:active { transform: scale(0.9); }
    
    .card-mobile { border-radius: 20px !important; }
    .subject-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.1); }
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1); }
    
    .x-small { font-size: 0.7rem; }
    .z-1 { z-index: 1; }
</style>
