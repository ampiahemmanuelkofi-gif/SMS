<div class="row g-4">
    <!-- Help Deck Header -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white p-4 overflow-hidden position-relative">
            <div class="row align-items-center">
                <div class="col-md-8 position-relative" style="z-index: 2;">
                    <h3 class="fw-bold mb-2">How can we help you today?</h3>
                    <p class="mb-3 opacity-75">Search our knowledge base or open a support ticket for technical assistance.</p>
                    <div class="input-group input-group-lg bg-white rounded shadow-sm">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0" placeholder="Search for answers...">
                    </div>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <i class="bi bi-headset opacity-25" style="font-size: 150px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket System -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">My Support Tickets</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                    <i class="bi bi-plus-lg me-1"></i> New ticket
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Ticket</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th class="pe-4 text-end">Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tickets)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-ticket-perforated fs-1 d-block mb-2"></i>
                                        No active tickets found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?php echo $ticket['subject']; ?></div>
                                            <small class="text-muted">ID: #TK-<?php echo str_pad($ticket['id'], 4, '0', STR_PAD_LEFT); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo ($ticket['status'] == 'open') ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo ucfirst($ticket['status']); ?>
                                            </span>
                                        </td>
                                        <td><small class="text-muted text-uppercase"><?php echo $ticket['category']; ?></small></td>
                                        <td class="pe-4 text-end small">
                                            <?php echo date('M d, Y', strtotime($ticket['updated_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Resources -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">Common Inquiries</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush small">
                    <?php foreach (array_slice($faqs, 0, 5) as $faq): ?>
                        <a href="<?php echo BASE_URL; ?>support/faq" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?php echo $faq['question']; ?>
                            <i class="bi bi-chevron-right text-muted x-small"></i>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?php echo BASE_URL; ?>support/faq" class="list-group-item list-group-item-action text-center py-3 text-primary fw-bold">
                        Browse Full Knowledge Base
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-soft-info">
            <div class="card-body text-center py-4">
                <i class="bi bi-mic fs-2 text-info mb-3 d-block"></i>
                <h6 class="fw-bold">Suggest a Feature</h6>
                <p class="small text-muted mb-3 px-3">Help us shape the future of EAMP School Pro. Tell us what you need!</p>
                <a href="<?php echo BASE_URL; ?>support/feedback" class="btn btn-info btn-sm text-white px-4">Submit Feedback</a>
            </div>
        </div>
    </div>
</div>

<!-- New Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Open Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>support/create_ticket" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Brief summary of the issue">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category" class="form-select">
                                <option value="technical">Technical Issue</option>
                                <option value="bug">Bug Report</option>
                                <option value="billing">Billing</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Describe your issue</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Provide as much detail as possible..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-soft-info { background-color: #e3f2fd; }
    .x-small { font-size: 10px; }
</style>
