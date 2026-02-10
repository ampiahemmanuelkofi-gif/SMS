<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-stack"></i> Consumable Stock Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="bi bi-plus-lg me-1"></i> Add Inventory Item
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Stock Level</th>
                            <th>Min Trigger</th>
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consumables as $c): ?>
                            <tr>
                                <td><strong><?php echo Security::clean($c['item_name']); ?></strong><br><small class="text-muted">SKU: <?php echo $c['sku']; ?></small></td>
                                <td><span class="badge bg-light text-dark border"><?php echo Security::clean($c['category_name']); ?></span></td>
                                <td>
                                    <?php if ($c['current_stock'] <= $c['min_stock_level']): ?>
                                        <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $c['current_stock']; ?></span>
                                    <?php else: ?>
                                        <span class="text-success fw-bold"><?php echo $c['current_stock']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $c['min_stock_level']; ?></td>
                                <td><?php echo $c['unit_of_measure']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStockModal<?php echo $c['id']; ?>">
                                        <i class="bi bi-plus-minus"></i> Stock adjustment
                                    </button>
                                </td>
                            </tr>

                            <!-- Update Stock Modal for this item -->
                            <div class="modal fade" id="updateStockModal<?php echo $c['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Adjust Stock: <?php echo Security::clean($c['item_name']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo BASE_URL; ?>inventory/stock" method="POST">
                                            <input type="hidden" name="action" value="update_stock">
                                            <input type="hidden" name="consumable_id" value="<?php echo $c['id']; ?>">
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Action Type</label>
                                                    <select name="log_type" class="form-select" required>
                                                        <option value="add">Add to Stock (Restock)</option>
                                                        <option value="subtract">Subtract from Stock (Usage)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Quantity (<?php echo $c['unit_of_measure']; ?>)</label>
                                                    <input type="number" name="quantity" class="form-control" required min="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Reason/Reference</label>
                                                    <input type="text" name="reason" class="form-control" placeholder="e.g. Monthly restock, Jan usage" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary px-4 fw-bold">Update Inventory</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">New Inventory Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>inventory/stock" method="POST">
                <input type="hidden" name="action" value="add_item">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit of Measure</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. reams, pieces, boxes" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Min Stock Warning Level</label>
                        <input type="number" name="min_stock" class="form-control" value="5" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold">Create Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
