<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>students">Students</a></li>
                <li class="breadcrumb-item active">Admit Student</li>
            </ol>
        </nav>
        <h2 class="page-title"><i class="bi bi-person-plus-fill"></i> Admit New Student</h2>
    </div>
</div>

<div class="table-card">
    <form action="<?php echo BASE_URL; ?>students/add" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
        
        <div class="row">
            <!-- Personal Info -->
            <div class="col-md-12 mb-3">
                <h5 class="border-bottom pb-2">Personal Information</h5>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Photo (JPG/PNG, Max 5MB)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Admission Date</label>
                <input type="date" name="admission_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Student Category</label>
                <select name="category_id" class="form-select">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo Security::clean($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Academic Assignment -->
            <div class="col-md-12 mt-4 mb-3">
                <h5 class="border-bottom pb-2">Academic Assignment</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label required">Class</label>
                <select name="class_id" class="form-select" onchange="loadSections(this.value)" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>">
                            <?php echo Security::clean($class['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label required">Section</label>
                <select name="section_id" id="section_id" class="form-select" required>
                    <option value="">Select Section</option>
                </select>
            </div>

            <!-- Guardian Info -->
            <div class="col-md-12 mt-4 mb-3">
                <h5 class="border-bottom pb-2">Guardian Information</h5>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Guardian Name</label>
                <input type="text" name="guardian_name" class="form-control" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Guardian Phone</label>
                <input type="tel" name="guardian_phone" class="form-control" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Guardian Email</label>
                <input type="email" name="guardian_email" class="form-control">
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Residential Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>
        </div>
        
        <div class="mt-4 border-top pt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle"></i> Save Student
            </button>
            <a href="<?php echo BASE_URL; ?>students" class="btn btn-outline-secondary px-4">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.innerHTML = '<option value="">Error!</option>';
        });
}
</script>
