<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: var(--z-toast, 1080);">
    <!-- Toasts will be dynamically added here -->
</div>

<script>
/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - success, error, warning, info
 * @param {number} duration - Auto-hide delay in ms (default 5000)
 */
function showToast(message, type = 'info', duration = 5000) {
    const container = document.querySelector('.toast-container');
    if (!container) return;
    
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };
    
    const bgColors = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-primary'
    };
    
    const toastId = 'toast-' + Date.now();
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgColors[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi ${icons[type]}"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl, { delay: duration });
    toast.show();
    
    // Remove from DOM after hidden
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

// Convenience functions
function toastSuccess(message) { showToast(message, 'success'); }
function toastError(message) { showToast(message, 'error'); }
function toastWarning(message) { showToast(message, 'warning'); }
function toastInfo(message) { showToast(message, 'info'); }
</script>
