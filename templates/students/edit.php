<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>students">Students</a></li>
                <li class="breadcrumb-item active">Edit Student</li>
            </ol>
        </nav>
        <h2 class="page-title"><i class="bi bi-pencil-square"></i> Edit Student: <?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></h2>
    </div>
</div>

<div class="table-card">
    <form action="<?php echo BASE_URL; ?>students/edit/<?php echo $student['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <h5 class="border-bottom pb-2">Personal Information</h5>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo Security::clean($student['first_name']); ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo Security::clean($student['last_name']); ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="male" <?php echo $student['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo $student['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="<?php echo $student['date_of_birth']; ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Update Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <?php if ($student['photo']): ?>
                    <small class="text-muted">Leave blank to keep current photo</small>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Status</label>
                <select name="status" class="form-select" required>
                    <option value="active" <?php echo $student['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $student['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="transferred" <?php echo $student['status'] === 'transferred' ? 'selected' : ''; ?>>Transferred</option>
                    <option value="graduated" <?php echo $student['status'] === 'graduated' ? 'selected' : ''; ?>>Graduated</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Student Category</label>
                <select name="category_id" class="form-select">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $student['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12 mt-4 mb-3">
                <h5 class="border-bottom pb-2">Academic Information</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label required">Class</label>
                <select name="class_id" class="form-select" onchange="loadSections(this.value)" required>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo $student['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($class['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label required">Section</label>
                <select name="section_id" id="section_id" class="form-select" required>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo $section['id']; ?>" <?php echo $student['section_id'] == $section['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($section['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12 mt-4 mb-3">
                <h5 class="border-bottom pb-2">Guardian Information</h5>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Guardian Name</label>
                <input type="text" name="guardian_name" class="form-control" value="<?php echo Security::clean($student['guardian_name']); ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label required">Guardian Phone</label>
                <input type="tel" name="guardian_phone" class="form-control" value="<?php echo Security::clean($student['guardian_phone']); ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Guardian Email</label>
                <input type="email" name="guardian_email" class="form-control" value="<?php echo Security::clean($student['guardian_email']); ?>">
            </div>
            
            <div class="col-md-12 mb-3">
                <label class="form-label">Residential Address</label>
                <textarea name="address" class="form-control" rows="2"><?php echo Security::clean($student['address']); ?></textarea>
            </div>
        </div>
        
        <div class="mt-4 border-top pt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle"></i> Update Student
            </button>
            <a href="<?php echo BASE_URL; ?>students/view_profile/<?php echo $student['id']; ?>" class="btn btn-outline-secondary px-4">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
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
