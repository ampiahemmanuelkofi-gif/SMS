<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-gear-fill"></i> LMS Integration Settings</h2>
        <a href="<?php echo BASE_URL; ?>lms" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to LMS
        </a>
    </div>
</div>

<div class="row">
    <?php foreach ($platforms as $platform): ?>
        <div class="col-md-12 mb-4">
            <div class="table-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <?php if ($platform['platform_key'] == 'google'): ?>
                            <i class="bi bi-google text-primary"></i>
                        <?php elseif ($platform['platform_key'] == 'microsoft'): ?>
                            <i class="bi bi-microsoft text-info"></i>
                        <?php else: ?>
                            <i class="bi bi-mortarboard text-secondary"></i>
                        <?php endif; ?>
                        <?php echo $platform['name']; ?> Configuration
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active_<?php echo $platform['id']; ?>" <?php echo $platform['is_active'] ? 'checked' : ''; ?> disabled>
                        <label class="form-check-label" for="active_<?php echo $platform['id']; ?>">Active</label>
                    </div>
                </div>

                <form action="<?php echo BASE_URL; ?>lms/update_settings" method="POST">
                    <input type="hidden" name="id" value="<?php echo $platform['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client ID / App ID</label>
                            <input type="text" name="client_id" class="form-control" value="<?php echo Security::clean($platform['client_id']); ?>" placeholder="Enter Client ID">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client Secret / API Key</label>
                            <input type="password" name="client_secret" class="form-control" value="<?php echo Security::clean($platform['client_secret']); ?>" placeholder="Enter Secret">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Redirect URI</label>
                            <input type="text" name="redirect_uri" class="form-control" value="<?php echo Security::clean($platform['redirect_uri']); ?>" placeholder="https://your-school.edu/lms/callback/<?php echo $platform['platform_key']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Enable Integration</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?php echo $platform['is_active'] ? 'selected' : ''; ?>>Yes, active</option>
                                <option value="0" <?php echo !$platform['is_active'] ? 'selected' : ''; ?>>No, disabled</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save <?php echo $platform['name']; ?> Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="alert alert-info shadow-sm mt-4">
    <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle-fill"></i> Developer Note:</h6>
    <p class="mb-0 small">
        To configure **Google Classroom**, create a project in the Google Cloud Console and enable the Classroom API. For **Microsoft Teams**, use the Azure Portal to register an application for "School Data Sync" or "MS Graph".
    </p>
</div>
