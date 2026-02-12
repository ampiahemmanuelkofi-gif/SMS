<?php
/**
 * Role Permission Matrix Template
 */
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">Role Permission Matrix</h3>
                            <p class="text-muted mb-0">Manage module access for all system roles</p>
                        </div>
                        <a href="<?= BASE_URL ?>admin" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Admin
                        </a>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Note:</strong> Super Administrators always have full access to all modules and cannot be modified here.
                    </div>

                    <form action="<?= BASE_URL ?>admin/permissions" method="POST">
                        <?= Security::csrfInput(); ?>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="min-width: 200px;">Module / Role</th>
                                        <?php foreach ($roles as $roleKey => $roleName): ?>
                                            <?php if ($roleKey === 'super_admin') continue; ?>
                                            <th class="text-center" style="min-width: 120px;">
                                                <div class="small fw-bold"><?= $roleName ?></div>
                                                <div class="x-small text-muted font-monospace"><?= $roleKey ?></div>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($modules as $modKey => $modName): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold">
                                                <i class="bi bi-box me-2 opacity-50"></i>
                                                <?= $modName ?>
                                            </td>
                                            <?php foreach ($roles as $roleKey => $roleName): ?>
                                                <?php if ($roleKey === 'super_admin') continue; ?>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[<?= $roleKey ?>][<?= $modKey ?>]"
                                                               value="1"
                                                            <?= (isset($matrix[$roleKey][$modKey]) && $matrix[$roleKey][$modKey]) ? 'checked' : '' ?>>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Save Permission Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.form-check-input {
    cursor: pointer;
    width: 2.5em;
    height: 1.25em;
}
.x-small {
    font-size: 0.7rem;
}
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}
.table thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 10;
}
</style>
