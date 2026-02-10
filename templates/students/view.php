<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>students">Students</a></li>
                <li class="breadcrumb-item active">Student Profile</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-title"><i class="bi bi-person-bounding-box"></i> Student Profile</h2>
            <div>
                <a href="<?php echo BASE_URL; ?>students/edit/<?php echo $student['id']; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit Basic Info
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar Profile Info -->
    <div class="col-md-3 mb-4">
        <div class="table-card text-center mb-4">
            <div class="user-avatar mx-auto mb-3" style="width: 120px; height: 120px; font-size: 50px;">
                <?php if ($student['photo']): ?>
                    <img src="<?php echo BASE_URL; ?>uploads/students/<?php echo $student['photo']; ?>" 
                         alt="Student" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                <?php else: ?>
                    <?php echo strtoupper(substr($student['first_name'], 0, 1)); ?>
                <?php endif; ?>
            </div>
            <h5 class="mb-1"><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></h5>
            <p class="text-muted small mb-2"><?php echo Security::clean($student['student_id']); ?></p>
            <span class="badge bg-<?php echo $student['status'] === 'active' ? 'success' : 'danger'; ?> mb-3">
                <?php echo ucfirst($student['status']); ?>
            </span>
            <div class="list-group list-group-flush text-start small mt-2">
                <div class="list-group-item d-flex justify-content-between p-2">
                    <span>Class:</span> <strong><?php echo Security::clean($student['class_name']); ?></strong>
                </div>
                <div class="list-group-item d-flex justify-content-between p-2">
                    <span>Category:</span> <strong><?php echo Security::clean($student['category_name'] ?: 'N/A'); ?></strong>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="table-card p-3">
            <h6 class="mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="<?php echo BASE_URL; ?>fees/student/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-success text-start">
                    <i class="bi bi-cash-stack"></i> Fee Records
                </a>
                <a href="<?php echo BASE_URL; ?>assessments/student/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary text-start">
                    <i class="bi bi-graph-up"></i> Academic Report
                </a>
                <a href="<?php echo BASE_URL; ?>reports/student_card/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-secondary text-start">
                    <i class="bi bi-card-heading"></i> Generate ID Card
                </a>
                <a href="<?php echo BASE_URL; ?>ai/predict/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-danger text-start">
                    <i class="bi bi-graph-up-arrow"></i> AI Prediction
                </a>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div class="col-md-9">
        <ul class="nav nav-pills mb-3 bg-white p-2 rounded shadow-sm border" id="profileTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview">Overview</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="medical-tab" data-bs-toggle="pill" data-bs-target="#medical">Medical</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="discipline-tab" data-bs-toggle="pill" data-bs-target="#discipline">Discipline</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="siblings-tab" data-bs-toggle="pill" data-bs-target="#siblings">Siblings</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="documents-tab" data-bs-toggle="pill" data-bs-target="#documents">Documents</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="custom-tab" data-bs-toggle="pill" data-bs-target="#custom">Additional Info</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="history-tab" data-bs-toggle="pill" data-bs-target="#history">History</button>
            </li>
        </ul>

        <div class="tab-content border-0 p-0" id="profileTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview">
                <div class="table-card p-4">
                    <h5 class="mb-4 border-bottom pb-2">Information Summary</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-primary small fw-bold">Personal Details</h6>
                            <table class="table table-sm table-borderless mt-2">
                                <tr><td class="text-muted" width="40%">Gender:</td><td><?php echo ucfirst($student['gender']); ?></td></tr>
                                <tr><td class="text-muted">DOB:</td><td><?php echo date('M d, Y', strtotime($student['date_of_birth'])); ?></td></tr>
                                <tr><td class="text-muted">Admission:</td><td><?php echo date('M d, Y', strtotime($student['admission_date'])); ?></td></tr>
                                <tr><td class="text-muted">Address:</td><td><?php echo Security::clean($student['address'] ?: 'N/A'); ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary small fw-bold">Guardian Details</h6>
                            <table class="table table-sm table-borderless mt-2">
                                <tr><td class="text-muted" width="40%">Name:</td><td><?php echo Security::clean($student['guardian_name']); ?></td></tr>
                                <tr><td class="text-muted">Phone:</td><td><?php echo Security::clean($student['guardian_phone']); ?></td></tr>
                                <tr><td class="text-muted">Email:</td><td><?php echo Security::clean($student['guardian_email'] ?: 'N/A'); ?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Tab -->
            <div class="tab-pane fade" id="medical">
                <div class="table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">Health & Medical Information</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#medicalModal">
                            <i class="bi bi-pencil"></i> Update
                        </button>
                    </div>
                    <?php if ($medical): ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small d-block">Blood Group</label>
                                <strong><?php echo Security::clean($medical['blood_group'] ?: 'Not Specified'); ?></strong>
                            </div>
                            <div class="col-md-8">
                                <label class="text-muted small d-block">Emergency Contact</label>
                                <strong><?php echo Security::clean($medical['emergency_contact_name']); ?> (<?php echo Security::clean($medical['emergency_contact_phone']); ?>)</strong>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small d-block">Allergies</label>
                                <p class="mb-0"><?php echo nl2br(Security::clean($medical['allergies'] ?: 'None recorded')); ?></p>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small d-block">Medical Conditions</label>
                                <p class="mb-0"><?php echo nl2br(Security::clean($medical['medical_conditions'] ?: 'None recorded')); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No medical information recorded yet.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Discipline Tab -->
            <div class="tab-pane fade" id="discipline">
                <div class="table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">Disciplinary Records</h5>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#disciplineModal">
                            <i class="bi bi-plus-circle"></i> Log Incident
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Action Taken</th>
                                    <th>Recorded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($disciplinary)): ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No disciplinary records found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($disciplinary as $record): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($record['incident_date'])); ?></td>
                                            <td><?php echo Security::clean($record['incident_description']); ?></td>
                                            <td><?php echo Security::clean($record['action_taken']); ?></td>
                                            <td><?php echo Security::clean($record['recorded_by_name']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Siblings Tab -->
            <div class="tab-pane fade" id="siblings">
                <div class="table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">Sibling Relationships</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#siblingModal">
                            <i class="bi bi-link"></i> Link Sibling
                        </button>
                    </div>
                    <div class="row">
                        <?php if (empty($siblings)): ?>
                            <div class="col-12 text-center py-4 text-muted">No sibling connections found.</div>
                        <?php else: ?>
                            <?php foreach ($siblings as $sibling): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded d-flex align-items-center">
                                        <div class="user-avatar me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                            <?php echo strtoupper(substr($sibling['first_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo Security::clean($sibling['first_name'] . ' ' . $sibling['last_name']); ?></h6>
                                            <span class="text-muted small"><?php echo Security::clean($sibling['class_name']); ?> | <?php echo Security::clean($sibling['relationship_type']); ?></span>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="<?php echo BASE_URL; ?>students/view_profile/<?php echo $sibling['sibling_student_id']; ?>" class="btn btn-sm btn-link"><i class="bi bi-eye"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents">
                <div class="table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">Student Documents</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#documentModal">
                            <i class="bi bi-upload"></i> Upload Document
                        </button>
                    </div>
                    <div class="row g-3">
                        <?php if (empty($documents)): ?>
                            <div class="col-12 text-center py-4 text-muted">No documents uploaded yet.</div>
                        <?php else: ?>
                            <?php foreach ($documents as $doc): ?>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <i class="bi bi-file-earmark-text fs-2 text-primary mb-2"></i>
                                        <h6 class="text-truncate" title="<?php echo Security::clean($doc['document_name']); ?>"><?php echo Security::clean($doc['document_name']); ?></h6>
                                        <span class="text-muted small d-block mb-2"><?php echo Security::clean($doc['document_type']); ?></span>
                                        <a href="<?php echo BASE_URL; ?>uploads/documents/<?php echo $doc['file_path']; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">View File</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Additional Info (Custom Fields) -->
            <div class="tab-pane fade" id="custom">
                <div class="table-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">Additional Information</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#customFieldsModal">
                            <i class="bi bi-pencil"></i> Update Fields
                        </button>
                    </div>
                    <div class="row">
                        <?php if (empty($customFields)): ?>
                            <div class="col-12 text-center py-4 text-muted">No additional fields configured or filled.</div>
                        <?php else: ?>
                            <?php foreach ($customFields as $field): ?>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small"><?php echo Security::clean($field['field_name']); ?></label>
                                    <p class="border-bottom pb-1"><strong><?php echo Security::clean($field['field_value'] ?: 'N/A'); ?></strong></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history">
                <div class="table-card p-4">
                    <h5 class="mb-4">Academic Promotion History</h5>
                    <div class="timeline timeline-small mt-3">
                        <?php if (empty($academicHistory)): ?>
                            <div class="text-muted py-3">No promotion history records found.</div>
                        <?php else: ?>
                            <?php foreach ($academicHistory as $entry): ?>
                                <div class="timeline-item pb-3 mb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <strong><?php echo Security::clean($entry['academic_year']); ?></strong>
                                        <span class="text-muted small"><?php echo date('M Y', strtotime($entry['promotion_date'])); ?></span>
                                    </div>
                                    <div class="small">
                                        Promoted from <span class="badge bg-secondary"><?php echo Security::clean($entry['from_section']); ?></span> 
                                        to <span class="badge bg-primary"><?php echo Security::clean($entry['to_section']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for SIS Expansion -->

<!-- Medical Modal -->
<div class="modal fade" id="medicalModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/save_medical" method="POST" class="modal-content">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Medical Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                                <option value="<?php echo $bg; ?>" <?php echo (isset($medical['blood_group']) && $medical['blood_group'] === $bg) ? 'selected' : ''; ?>><?php echo $bg; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="<?php echo Security::clean($medical['emergency_contact_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" class="form-control" value="<?php echo Security::clean($medical['emergency_contact_phone'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2"><?php echo Security::clean($medical['allergies'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Medical Conditions</label>
                        <textarea name="medical_conditions" class="form-control" rows="2"><?php echo Security::clean($medical['medical_conditions'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Discipline Modal -->
<div class="modal fade" id="disciplineModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/add_discipline" method="POST" class="modal-content">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Log Disciplinary Incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Incident Date</label>
                    <input type="date" name="incident_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Incident Description</label>
                    <textarea name="incident_description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Action Taken</label>
                    <textarea name="action_taken" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Log Incident</button>
            </div>
        </form>
    </div>
</div>

<!-- Sibling Modal -->
<div class="modal fade" id="siblingModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/add_sibling" method="POST" class="modal-content">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Link Sibling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Search Student</label>
                    <div class="input-group">
                        <input type="text" id="siblingSearch" class="form-control" placeholder="Name or ID...">
                        <button class="btn btn-outline-secondary" type="button" onclick="searchForSibling()">Search</button>
                    </div>
                    <div id="siblingResults" class="mt-2 list-group"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Selected Sibling</label>
                    <input type="text" id="selectedSiblingName" class="form-control" readonly placeholder="No student selected">
                    <input type="hidden" name="sibling_id" id="selectedSiblingId" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Relationship</label>
                    <select name="relationship" class="form-select">
                        <option value="Sibling">Sibling</option>
                        <option value="Twin">Twin</option>
                        <option value="Half-Sibling">Half-Sibling</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnLinkSibling" disabled>Link Students</button>
            </div>
        </form>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/upload_document" method="POST" enctype="multipart/form-data" class="modal-content">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Document Display Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Birth Certificate" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Document Type</label>
                    <select name="type" class="form-select">
                        <option value="Identification">Identification</option>
                        <option value="Academic Record">Academic Record</option>
                        <option value="Admission Document">Admission Document</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select File (PDF, Images, Word)</label>
                    <input type="file" name="document" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Fields Modal -->
<div class="modal fade" id="customFieldsModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo BASE_URL; ?>students/save_custom_fields" method="POST" class="modal-content">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Update Additional Fields</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (empty($customFields)): ?>
                    <p class="text-muted">No custom fields defined in system settings.</p>
                <?php else: ?>
                    <?php foreach ($customFields as $field): ?>
                        <div class="mb-3">
                            <label class="form-label"><?php echo Security::clean($field['field_name']); ?></label>
                            <?php if ($field['field_type'] === 'select'): ?>
                                <select name="field[<?php echo $field['id']; ?>]" class="form-select">
                                    <option value="">Select Option</option>
                                    <?php 
                                        $options = explode(',', $field['field_options'] ?? '');
                                        foreach ($options as $opt): 
                                            $opt = trim($opt);
                                            if (empty($opt)) continue;
                                    ?>
                                        <option value="<?php echo $opt; ?>" <?php echo $field['field_value'] === $opt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($field['field_type'] === 'date'): ?>
                                <input type="date" name="field[<?php echo $field['id']; ?>]" class="form-control" value="<?php echo Security::clean($field['field_value'] ?? ''); ?>">
                            <?php else: ?>
                                <input type="<?php echo $field['field_type']; ?>" name="field[<?php echo $field['id']; ?>]" class="form-control" value="<?php echo Security::clean($field['field_value'] ?? ''); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Info</button>
            </div>
        </form>
    </div>
</div>

<script>
function searchForSibling() {
    const q = document.getElementById('siblingSearch').value;
    if (q.length < 2) return;
    
    fetch('<?php echo BASE_URL; ?>students/search_ajax?q=' + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            const results = document.getElementById('siblingResults');
            results.innerHTML = '';
            
            // Filter out current student
            const filtered = data.filter(s => s.id != <?php echo $student['id']; ?>);
            
            if (filtered.length === 0) {
                results.innerHTML = '<div class="list-group-item text-muted">No students found</div>';
                return;
            }
            
            filtered.forEach(s => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `<strong>${s.first_name} ${s.last_name}</strong> <small class="text-muted">(${s.student_id}) - ${s.class_name}</small>`;
                item.onclick = () => selectSibling(s.id, `${s.first_name} ${s.last_name}`);
                results.appendChild(item);
            });
        });
}

function selectSibling(id, name) {
    document.getElementById('selectedSiblingId').value = id;
    document.getElementById('selectedSiblingName').value = name;
    document.getElementById('siblingResults').innerHTML = '';
    document.getElementById('btnLinkSibling').disabled = false;
}

// Ensure active tab on page reload if hash present
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash) {
        const tabTriggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
        if (tabTriggerEl) {
            const tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
        }
    }
});
</script>
