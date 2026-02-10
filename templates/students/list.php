<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1"><i class="bi bi-people-fill me-2 text-primary"></i>Student Registry</h1>
        <p class="text-muted mb-0">View and manage all student enrollment records</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-gear me-1"></i> Bulk Actions
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print ID Cards</a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>students/export"><i class="bi bi-download me-2"></i>Export All</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload me-2"></i>Import Bulk</a></li>
            </ul>
        </div>
        <a href="<?php echo BASE_URL; ?>students/add" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Register Student
        </a>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/import" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Student Import</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle"></i> Please upload a CSV file with the following columns: <br>
                    <strong>First Name, Last Name, Gender, DOB (YYYY-MM-DD), Section ID, Guardian Name, Guardian Phone, Admission Date (YYYY-MM-DD)</strong>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select CSV File</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Start Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Filters & Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>students" method="GET" class="row g-3">
            <div class="col-lg-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" 
                           placeholder="Search name or student ID..." value="<?php echo Security::clean($filters['search']); ?>">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="class_id" class="form-select bg-light border-0" onchange="loadSections(this.value)">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo $filters['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($class['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <select name="section_id" id="section_id" class="form-select bg-light border-0">
                    <option value="">All Sections</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo $section['id']; ?>" <?php echo $filters['section_id'] == $section['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($section['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Apply
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Students Registry Table -->
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Student Registry <span class="badge bg-soft-primary text-primary ms-2"><?php echo count($students); ?> Total</span></h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print Registry</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Student</th>
                    <th>ID Number</th>
                    <th>Class / Section</th>
                    <th>Guardian Details</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-soft-primary text-primary" style="width: 42px; height: 42px;">
                                        <?php if ($student['photo']): ?>
                                            <img src="<?php echo BASE_URL; ?>uploads/students/<?php echo $student['photo']; ?>" 
                                                 class="rounded-circle w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <?php echo strtoupper(substr($student['first_name'], 0, 1)); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0 text-dark"><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></div>
                                        <small class="text-muted"><?php echo Security::clean($student['gender']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><code class="fw-bold"><?php echo Security::clean($student['student_id']); ?></code></td>
                            <td>
                                <div class="fw-medium"><?php echo Security::clean($student['class_name']); ?></div>
                                <div class="x-small text-muted"><?php echo Security::clean($student['section_name']); ?></div>
                            </td>
                            <td>
                                <div class="small fw-semibold"><?php echo Security::clean($student['guardian_name']); ?></div>
                                <div class="x-small text-muted"><i class="bi bi-telephone"></i> <?php echo Security::clean($student['guardian_phone']); ?></div>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?php echo $student['status'] === 'active' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger'; ?>">
                                    <?php echo ucfirst($student['status']); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="<?php echo BASE_URL; ?>students/view_profile/<?php echo $student['id']; ?>" 
                                       class="btn btn-sm btn-white border shadow-none" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>students/edit/<?php echo $student['id']; ?>" 
                                       class="btn btn-sm btn-white border shadow-none" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-people display-4 opacity-25"></i>
                                <h6 class="mt-3">No students found MATCHING your filters.</h6>
                                <p class="text-muted small">Try adjusting your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>students/get_sections/' + classId)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
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
