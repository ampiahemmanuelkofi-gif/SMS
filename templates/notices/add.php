<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Post New Notice</h4>
        <p class="text-muted small mb-0">Create an announcement for students, parents, or staff</p>
    </div>
    <a href="<?php echo BASE_URL; ?>notices" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Notices
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo BASE_URL; ?>notices/add" class="needs-validation" novalidate>
                    <?php echo Security::csrfInput(); ?>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label required">Notice Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               placeholder="Enter notice title" required>
                        <div class="invalid-feedback">Please enter a title for the notice.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label required">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="6" 
                                  placeholder="Write your notice content here..." required></textarea>
                        <div class="invalid-feedback">Please enter the notice content.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="audience" class="form-label required">Target Audience</label>
                            <select class="form-select" id="audience" name="audience" required>
                                <option value="">Select audience...</option>
                                <option value="all">All (Everyone)</option>
                                <option value="students">Students Only</option>
                                <option value="teachers">Teachers Only</option>
                                <option value="parents">Parents Only</option>
                                <option value="staff">Staff Only</option>
                            </select>
                            <div class="invalid-feedback">Please select a target audience.</div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="expires_at" class="form-label">Expiry Date (Optional)</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at">
                            <div class="form-text">Leave blank for no expiration</div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-megaphone me-1"></i> Publish Notice
                        </button>
                        <a href="<?php echo BASE_URL; ?>notices" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-soft-primary">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Tips</h6>
                <ul class="small mb-0">
                    <li class="mb-2">Keep notices clear and concise</li>
                    <li class="mb-2">Use "All" audience for important school-wide announcements</li>
                    <li class="mb-2">Set an expiry date for time-sensitive notices</li>
                    <li>Notices will appear on the dashboard and notice board</li>
                </ul>
            </div>
        </div>
    </div>
</div>
