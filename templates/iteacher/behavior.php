<div class="card-mobile p-4 bg-white">
    <h6 class="fw-bold border-bottom pb-2 mb-3">Quick Incident Report</h6>
    <form action="<?php echo BASE_URL; ?>iteacher/submit_incident" method="POST">
        <div class="mb-3">
            <label class="form-label small">Student Name</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Search Student --</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label small">Incident Category</label>
            <select name="category" class="form-select" required>
                <option value="disruption">Classroom Disruption</option>
                <option value="homework">Missing Homework</option>
                <option value="bullying">Bullying/Peer Conflict</option>
                <option value="positive">Positive Behavior (Star)</option>
                <option value="other">Other Incident</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label small">Incident Level</label>
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="level" id="l1" value="1" checked>
                <label class="btn btn-outline-success" for="l1">Lv 1</label>
                
                <input type="radio" class="btn-check" name="level" id="l2" value="2">
                <label class="btn btn-outline-warning" for="l2">Lv 2</label>
                
                <input type="radio" class="btn-check" name="level" id="l3" value="3">
                <label class="btn btn-outline-danger" for="l3">Lv 3</label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small">Brief Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Describe what happened..."></textarea>
        </div>

        <div class="d-grid pt-2">
            <button type="submit" class="btn btn-primary shadow-sm">
                <i class="bi bi-send-fill"></i> Submit Incident
            </button>
        </div>
    </form>
</div>

<div class="mt-4">
    <h6 class="fw-bold mb-3">Recent Reports</h6>
    <div class="card-mobile p-3 bg-white">
        <div class="d-flex justify-content-between align-items-center small">
            <div>
                <span class="fw-bold">John Doe</span>
                <span class="text-muted ms-2">Disruption</span>
            </div>
            <span class="badge bg-warning text-dark">Lv 2</span>
        </div>
        <div class="text-muted x-small mt-1">Today, 09:15</div>
    </div>
</div>

<style>
    .x-small { font-size: 10px; }
</style>
