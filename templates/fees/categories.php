<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-tags"></i> Fee Categories</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle"></i> Add Category
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($cat['name']); ?></strong></td>
                                <td><?php echo Security::clean($cat['description']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>fees/saveCategory" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <div class="modal-header"><h5>Add Fee Category</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Tuition, Boarding" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Category</button></div>
            </form>
        </div>
    </div>
</div>
