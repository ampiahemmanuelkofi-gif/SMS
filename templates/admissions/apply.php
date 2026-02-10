<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <h2 class="mb-1">Student Admission Application</h2>
                    <p class="mb-0 text-white-50">Please fill out the form below to apply for admission.</p>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>admissions/apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                        
                        <!-- Applicant Details -->
                        <h5 class="text-primary border-bottom pb-2 mb-3">Applicant Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label required">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label required">Applying For Class</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">Select Class...</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo $class['id']; ?>"><?php echo Security::clean($class['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Guardian Details -->
                        <h5 class="text-primary border-bottom pb-2 mb-3">Guardian Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label required">Parent/Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Phone Number</label>
                                <input type="tel" name="guardian_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="guardian_email" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label required">Residential Address</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>

                        <!-- Documents -->
                        <!-- <h5 class="text-primary border-bottom pb-2 mb-3">Documents (Optional)</h5>
                        <div class="mb-4">
                            <label class="form-label">Upload Birth Certificate / Report Card</label>
                            <input type="file" name="documents[]" class="form-control" multiple>
                        </div> -->

                        <div class="alert alert-light border small">
                            <i class="bi bi-info-circle-fill text-info"></i> By submitting this form, you attest that the information provided is accurate. Our admissions team will contact you regarding the next steps, including interview scheduling and assessments.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-light text-muted">Cancel and Return Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
