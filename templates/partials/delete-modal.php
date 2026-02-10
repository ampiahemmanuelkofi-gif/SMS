<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-xl);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="avatar avatar-xl bg-soft-danger text-danger mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2" id="deleteModalLabel">Confirm Deletion</h5>
                <p class="text-muted mb-4" id="deleteModalMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
                
                <form id="deleteForm" method="POST">
                    <?php echo Security::csrfInput(); ?>
                    <input type="hidden" name="delete_id" id="deleteItemId">
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Open delete confirmation modal
 * @param {string|number} id - Item ID to delete
 * @param {string} action - Form action URL
 * @param {string} message - Optional custom message
 */
function confirmDelete(id, action, message = null) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const itemId = document.getElementById('deleteItemId');
    const msgEl = document.getElementById('deleteModalMessage');
    
    itemId.value = id;
    form.action = action;
    
    if (message) {
        msgEl.textContent = message;
    } else {
        msgEl.textContent = 'Are you sure you want to delete this item? This action cannot be undone.';
    }
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>
