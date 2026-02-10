<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">Subjects</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="bi bi-plus-lg"></i> Add Subject
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card">
            <?php if (empty($subjects)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-book-half display-4 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No subjects defined yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Code</th>
                                <th>Level</th>
                                <th>Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($subject['name']); ?></strong></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo Security::clean($subject['code']); ?></span></td>
                                    <td><?php echo ucfirst($subject['level']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $subject['type'] === 'core' ? 'primary' : 'secondary'; ?>">
                                            <?php echo ucfirst($subject['type']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" disabled><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>academics/addSubject" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Subject Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mathematics, Science" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Subject Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. MATH101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Level</label>
                        <select name="level" class="form-select" required>
                            <option value="">Select Level...</option>
                            <option value="preschool">Preschool</option>
                            <option value="primary">Primary</option>
                            <option value="jhs">JHS</option>
                            <option value="shs">SHS</option>
                            <option value="all">All Levels</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="core">Core Subject</option>
                            <option value="elective">Elective</option>
                            <option value="extra">Extra-curricular</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
