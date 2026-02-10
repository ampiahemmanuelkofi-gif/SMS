<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item">
            <a href="<?php echo BASE_URL; ?>dashboard" class="text-decoration-none text-muted">
                <i class="bi bi-house-door"></i> Home
            </a>
        </li>
        <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <?php if (isset($crumb['active']) && $crumb['active']): ?>
                    <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">
                        <?php echo Security::clean($crumb['label']); ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo BASE_URL . $crumb['url']; ?>" class="text-decoration-none text-muted">
                            <?php echo Security::clean($crumb['label']); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php elseif (isset($pageTitle)): ?>
            <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">
                <?php echo Security::clean($pageTitle); ?>
            </li>
        <?php endif; ?>
    </ol>
</nav>

<style>
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\F285";
        font-family: "bootstrap-icons";
        font-size: 10px;
        color: var(--text-light);
    }
</style>
