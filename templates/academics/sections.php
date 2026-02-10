<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>academics/classes">Classes</a></li>
                <li class="breadcrumb-item active">Sections</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="page-title"><i class="bi bi-grid-3x3-gap-fill"></i> Sections for <?php echo Security::clean($class['name']); ?></h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                <i class="bi bi-plus-circle"></i> Add Section
            </button>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Section Name</th>
                    <th>Capacity</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sections)): ?>
                    <?php foreach ($sections as $section): ?>
                        <tr>
                            <td><strong><?php echo Security::clean($section['name']); ?></strong></td>
                            <td><?php echo $section['capacity'] ?: 'N/A'; ?></td>
                            <td>
                                <?php
                                $db = getDbConnection();
                                $students = $db->prepare("SELECT COUNT(*) FROM students WHERE section_id = ? AND status = 'active'");
                                $students->execute([$section['id']]);
                                echo $students->fetchColumn();
                                ?>
                            </td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editSection(<?php echo htmlspecialchars(json_encode($section)); ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No sections found for this class</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>academics/addSection" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Section A, Blue House" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity (Optional)</label>
                        <input type="number" name="capacity" class="form-control" placeholder="Max students">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Section</button>
                </div>
            </form>
        </div>
    </div>
</div>
