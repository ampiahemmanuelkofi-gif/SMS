<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-plus-circle-fill"></i> Post New Homework</h2>
        <p class="text-muted">Create a new assignment for your assigned classes.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="table-card shadow-sm">
            <form action="<?php echo BASE_URL; ?>homework/save" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                
                <div class="mb-3">
                    <label class="form-label required">Select Class & Subject</label>
                    <select name="class_subject" class="form-select" required onchange="updateHiddenFields(this)">
                        <option value="">-- Select --</option>
                        <?php foreach ($assignments as $a): ?>
                            <option value="<?php echo $a['section_id'] . '|' . $a['subject_id']; ?>">
                                <?php echo Security::clean($a['class_name'] . ' ' . $a['section_name'] . ' - ' . $a['subject_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="section_id" id="section_id">
                    <input type="hidden" name="subject_id" id="subject_id">
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Algebraic Expressions Exercise" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Description / Instructions</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Details of the homework..." required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required">Submission Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" required>
                </div>
                
                <div class="mt-4 pt-3 border-top d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-bold">
                        <i class="bi bi-megaphone"></i> Post Assignment
                    </button>
                    <a href="<?php echo BASE_URL; ?>homework" class="btn btn-link mt-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateHiddenFields(select) {
    const val = select.value;
    if (val) {
        const parts = val.split('|');
        document.getElementById('section_id').value = parts[0];
        document.getElementById('subject_id').value = parts[1];
    }
}
</script>
