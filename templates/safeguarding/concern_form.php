<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title text-danger"><i class="bi bi-journal-plus"></i> Record Welfare Concern</h2>
        <a href="<?php echo BASE_URL; ?>safeguarding" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Hub Overview
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="table-card p-5">
            <form action="<?php echo BASE_URL; ?>safeguarding/add" method="POST">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Select Student</label>
                        <select name="student_id" class="form-select form-select-lg border-danger" required>
                            <option value="">-- Search and Select Student --</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>">
                                    <?php echo $student['first_name'] . ' ' . $student['last_name']; ?> (<?php echo $student['student_id']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category of Concern</label>
                        <select name="category" class="form-select" required>
                            <option value="neglect">Neglect</option>
                            <option value="physical_abuse">Physical Abuse</option>
                            <option value="emotional_abuse">Emotional Abuse</option>
                            <option value="sexual_abuse">Sexual Abuse</option>
                            <option value="behavioral">Behavioral / Mental Health</option>
                            <option value="family_issues">Family / Domestic Issues</option>
                            <option value="other">Other Concern</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Severity Level</label>
                        <select name="severity" class="form-select" required>
                            <option value="low">Low (Monitoring)</option>
                            <option value="medium">Medium (Internal Action Needed)</option>
                            <option value="high">High (External Agency Consideration)</option>
                            <option value="critical">Critical (Immediate Safeguarding Action)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Incident Date</label>
                        <input type="date" name="incident_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Title / Summary</label>
                        <input type="text" name="title" class="form-control" placeholder="Short descriptive title" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Detailed Description of Concern</label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Document facts, observations, and direct quotes where possible. Avoid personal opinion." required></textarea>
                        <div class="form-text text-muted small mt-2">
                             <i class="bi bi-info-circle"></i> Ensure your documentation is objective and follows the school's safeguarding policy.
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="p-3 bg-light rounded text-center">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="bi bi-shield-fill-check"></i> Securely Record Concern
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
