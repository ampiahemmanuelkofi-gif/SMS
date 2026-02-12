<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="bg-secondary p-4 text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold">Welcome back, <?php echo Auth::getFullName(); ?>!</h4>
                        <p class="mb-0 opacity-75">Staff Dashboard</p>
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
                <h6 class="mb-0 fw-bold">Recent Updates</h6>
            </div>
            <div class="card-body py-5 text-center">
                <i class="bi bi-info-circle fs-1 text-muted opacity-25 mb-3"></i>
                <p class="text-muted">No recent updates targeted specifically for staff yet.</p>
                <button class="btn btn-outline-secondary btn-sm px-4 rounded-pill">View Directory</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">Staff Quick Links</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 border-0 d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2 text-primary"></i> 
                        <a href="#" class="text-decoration-none text-dark small">Staff Calendar</a>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2 text-success"></i> 
                        <a href="#" class="text-decoration-none text-dark small">Leave Request</a>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex align-items-center">
                        <i class="bi bi-chat-dots me-2 text-info"></i> 
                        <a href="#" class="text-decoration-none text-dark small">Communicate</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
