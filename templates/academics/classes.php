<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-book-fill"></i> Manage Classes</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="bi bi-plus-circle"></i> Add New Class
        </button>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Level</th>
                    <th>Sections</th>
                    <th>Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><strong><?php echo Security::clean($class['name']); ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?php echo ucfirst($class['level']); ?></span></td>
                        <td>
                            <?php
                            $db = getDbConnection();
                            $sections = $db->prepare("SELECT COUNT(*) FROM sections WHERE class_id = ?");
                            $sections->execute([$class['id']]);
                            echo $sections->fetchColumn();
                            ?>
                        </td>
                        <td>
                            <?php
                            $students = $db->prepare("SELECT COUNT(*) FROM students s JOIN sections sec ON s.section_id = sec.id WHERE sec.class_id = ? AND s.status = 'active'");
                            $students->execute([$class['id']]);
                            echo $students->fetchColumn();
                            ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>academics/sections/<?php echo $class['id']; ?>" class="btn btn-outline-primary" title="Manage Sections">
                                    <i class="bi bi-grid-3x3-gap-fill"></i> Sections
                                </a>
                                <button class="btn btn-outline-secondary" onclick="editClass(<?php echo htmlspecialchars(json_encode($class)); ?>)" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>academics/addClass" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Primary 1, JHS 3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Level</label>
                        <select name="level" class="form-select" required>
                            <option value="nursery">Nursery</option>
                            <option value="kindergarten">Kindergarten</option>
                            <option value="primary">Primary</option>
                            <option value="jhs">JHS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Class</button>
                </div>
            </form>
        </div>
    </div>
</div>
