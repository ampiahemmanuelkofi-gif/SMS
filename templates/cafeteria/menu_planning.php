<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-calendar-plus"></i> Weekly Menu Planning</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
            <i class="bi bi-plus-lg me-1"></i> Add Menu Item
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-info">
                        <tr>
                            <th>Meal Date</th>
                            <th>Time / Type</th>
                            <th>Menu Item</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $m): ?>
                            <tr>
                                <td><strong><?php echo date('D, M d', strtotime($m['meal_date'])); ?></strong></td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?php echo $m['type_name']; ?></span><br>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($m['start_time'])); ?></small>
                                </td>
                                <td><strong><?php echo Security::clean($m['menu_item']); ?></strong></td>
                                <td><small><?php echo Security::clean($m['description']); ?></small></td>
                                <td><span class="badge bg-success">Published</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($menus)): ?>
                            <tr><td colspan="6" class="text-center py-5">No menus planned for the upcoming week.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Schedule New Menu Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>cafeteria/menus" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Date</label>
                        <input type="date" name="meal_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meal Type</label>
                        <select name="meal_type_id" class="form-select" required>
                            <?php foreach ($meal_types as $mt): ?>
                                <option value="<?php echo $mt['id']; ?>"><?php echo $mt['type_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Menu Item Title</label>
                        <input type="text" name="menu_item" class="form-control" placeholder="e.g., Grilled Chicken & Rice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description (Ingredients/Details)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Publish to Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>
