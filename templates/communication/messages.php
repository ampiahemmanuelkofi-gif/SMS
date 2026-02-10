<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-chat-dots"></i> Messaging Inbox</h2>
        <a href="<?php echo BASE_URL; ?>communication/send" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Message
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="list-group list-group-flush inbox-list">
                <a href="<?php echo BASE_URL; ?>communication/inbox" class="list-group-item list-group-item-action active p-3">
                    <i class="bi bi-inbox me-2"></i> Inbox
                </a>
                <a href="<?php echo BASE_URL; ?>communication/sent" class="list-group-item list-group-item-action p-3">
                    <i class="bi bi-send me-2"></i> Sent Mail
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;"></th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $m): ?>
                            <tr class="<?php echo $m['is_read'] ? 'opacity-75' : 'fw-bold'; ?>">
                                <td>
                                    <i class="bi <?php echo $m['is_read'] ? 'bi-envelope-open' : 'bi-envelope-fill text-primary'; ?>"></i>
                                </td>
                                <td><?php echo Security::clean($m['sender_name']); ?></td>
                                <td><?php echo Security::clean($m['subject']); ?></td>
                                <td><small class="text-muted"><?php echo date('M d', strtotime($m['created_at'])); ?></small></td>
                                <td>
                                    <button class="btn btn-sm btn-light border" title="View Message"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($messages)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Your inbox is empty.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.inbox-list .list-group-item.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}
</style>
