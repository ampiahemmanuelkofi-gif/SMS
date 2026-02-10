<div class="row g-4">
    <!-- Backup Center -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cloud-arrow-down-fill text-primary"></i> Database Backups</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Generate a full snapshot of your database including all student records, academic history, and system configurations.</p>
                
                <form action="<?php echo BASE_URL; ?>admin/maintenance" method="POST" class="mb-4">
                    <input type="hidden" name="action" value="backup">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-database-fill-gear me-2"></i> Generate New Backup
                    </button>
                </form>

                <h7 class="fw-bold small d-block mb-3">Available Backups</h7>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                        <div>
                            <div class="fw-bold">backup_2026-02-05.sql</div>
                            <small class="text-muted">Size: 42.5 MB</small>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-0">
                        <div>
                            <div class="fw-bold">backup_2026-01-30.sql</div>
                            <small class="text-muted">Size: 41.2 MB</small>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Cleanup -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 text-danger">
                <h6 class="mb-0 fw-bold"><i class="bi bi-trash3-fill"></i> Data Archival & Purging</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning small border-0 py-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> These actions are irreversible.
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small">Archive Old Data</label>
                    <select class="form-select form-select-sm mb-2">
                        <option>Academic Years prior to 2024</option>
                        <option>Inactive Audit Logs older than 1 year</option>
                    </select>
                    <button class="btn btn-sm btn-outline-warning w-100">Move to Archive</button>
                </div>

                <hr>

                <div class="mb-2">
                    <label class="form-label fw-bold small text-danger">Purge Temporary Data</label>
                    <p class="x-small text-muted">Remove session logs, orphan files, and temporary cache entries.</p>
                    <button class="btn btn-sm btn-danger w-100">Purge Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 11px; }
</style>
