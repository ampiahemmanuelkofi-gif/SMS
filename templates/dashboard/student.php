<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="bg-primary p-4 text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold">Welcome back, <?php echo Auth::getFullName(); ?>!</h4>
                        <p class="mb-0 opacity-75">Student Portal Dashboard</p>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                        <i class="bi bi-person-badge fs-3 text-white"></i>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="text-muted mb-2">My Profile</h6>
                                <p class="mb-0 fw-bold"><?php echo Auth::getRole(); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="text-muted mb-2">Current Date</h6>
                                <p class="mb-0 fw-bold"><?php echo date('F d, Y'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="text-muted mb-2">Status</h6>
                                <p class="mb-0 fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> Active Session</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder for module specific widgets -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">Recent Activity</h6>
            </div>
            <div class="card-body py-5 text-center">
                <img src="<?php echo BASE_URL; ?>assets/img/empty-activity.svg" alt="Empty" style="width: 120px; opacity: 0.3;" class="mb-3">
                <p class="text-muted">No recent activity to display for your role yet.</p>
                <button class="btn btn-outline-primary btn-sm px-4 rounded-pill">Explore Modules</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">System Announcements</h6>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="bg-soft-warning text-warning p-2 rounded me-3">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <p class="mb-0 small fw-bold">System Update</p>
                        <p class="mb-0 x-small text-muted">New modules have been activated for your role.</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-soft-info text-info p-2 rounded me-3">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div>
                        <p class="mb-0 small fw-bold">Welcome!</p>
                        <p class="mb-0 x-small text-muted">Welcome to the new Student Portal dashboard portal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-soft-warning { background-color: rgba(255, 193, 7, 0.15); }
.bg-soft-info { background-color: rgba(13, 202, 240, 0.15); }
.x-small { font-size: 0.75rem; }
</style>