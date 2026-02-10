<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">Academic Programs</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgramModal">
            <i class="bi bi-plus-lg"></i> Add Program
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card">
            <?php if (empty($programs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-book display-4 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No academic programs defined yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Program Name</th>
                                <th>Description</th>
                                <th>Date Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programs as $program): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($program['name']); ?></strong></td>
                                    <td><?php echo Security::clean($program['description']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($program['created_at'])); ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Edit not implemented yet"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" disabled title="Delete not implemented yet"><i class="bi bi-trash"></i></button>
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

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Academic Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>academics/addProgram" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Program Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. GES, British Curricula, IB" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
