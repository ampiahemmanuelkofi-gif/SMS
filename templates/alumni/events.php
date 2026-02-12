<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event-fill me-2"></i> Alumni Events</h5>
        <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
            <a href="<?= BASE_URL ?>alumni/addEvent" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Add Event
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($events)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm py-5 text-center" style="border-radius: 15px;">
                <img src="<?= BASE_URL ?>assets/img/empty-events.svg" alt="Empty" style="width: 120px; opacity: 0.3;" class="mb-3 d-block mx-auto">
                <p class="text-muted">No upcoming alumni events found.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-soft-info text-info p-2 rounded" style="width: 50px; text-align: center;">
                                <div class="fw-bold fs-5"><?= date('d', strtotime($event['event_date'])) ?></div>
                                <div class="small fw-bold text-uppercase"><?= date('M', strtotime($event['event_date'])) ?></div>
                            </div>
                            <span class="badge bg-soft-success text-success rounded-pill px-3">Upcoming</span>
                        </div>
                        <h6 class="fw-bold mb-2"><?= $event['title'] ?></h6>
                        <p class="text-muted small mb-3"><?= substr($event['description'], 0, 100) ?><?= strlen($event['description']) > 100 ? '...' : '' ?></p>
                        <div class="d-flex flex-column gap-2 small text-muted">
                            <span><i class="bi bi-geo-alt me-2"></i><?= $event['location'] ?? 'Online' ?></span>
                            <span><i class="bi bi-clock me-2"></i><?= date('h:i A', strtotime($event['event_time'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
.bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
</style>
