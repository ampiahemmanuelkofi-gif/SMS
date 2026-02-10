<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-pencil"></i> Compose New Message</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>communication/send" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <select name="receiver_id" class="form-select select2" required>
                            <option value="">Search for user...</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo Security::clean($user['full_name']); ?> (<?php echo ucfirst($user['role']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Enter message subject" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="8" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?php echo BASE_URL; ?>communication/inbox" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Send Message <i class="bi bi-send ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
