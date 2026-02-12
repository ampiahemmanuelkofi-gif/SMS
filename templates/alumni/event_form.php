<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle-fill me-2 text-primary"></i> Add Alumni Event</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>alumni/addEvent" method="POST">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Event Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="Annual Alumni Reunion">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Time</label>
                            <input type="time" name="event_time" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Main Hall / Online">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Event details..."></textarea>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light rounded-pill px-4 me-2" onclick="history.back()">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Create Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
