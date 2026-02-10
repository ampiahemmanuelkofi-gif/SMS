/**
 * Enterprise SaaS Application JavaScript
 * Consolidated scripts for School Management System
 */

(function ($) {
    'use strict';

    // ========== GLOBAL CONFIGURATION ==========
    const App = {
        baseUrl: typeof BASE_URL !== 'undefined' ? BASE_URL : '/',

        init: function () {
            this.initSidebar();
            this.initDataTables();
            this.initFormValidation();
            this.initDeleteConfirmation();
            this.initTooltips();
            this.initLoadingStates();
            console.log('🚀 App initialized');
        },

        // ========== SIDEBAR ==========
        initSidebar: function () {
            // Mobile toggle
            $(document).on('click', '.sidebar-toggle-mobile, .mobile-menu-toggle', function () {
                $('.sidebar').toggleClass('show');
                $('.sidebar-overlay').toggleClass('show');
            });

            // Close sidebar on overlay click
            $(document).on('click', '.sidebar-overlay', function () {
                $('.sidebar').removeClass('show');
                $(this).removeClass('show');
            });

            // Auto-expand active submenu
            const currentPath = window.location.pathname;
            $('.sidebar .nav-link').each(function () {
                const href = $(this).attr('href');
                if (href && currentPath.includes(href.split('/').pop())) {
                    $(this).addClass('active');
                    $(this).closest('.collapse').addClass('show');
                    $(this).closest('.collapse').prev('.nav-link').removeClass('collapsed');
                }
            });
        },

        // ========== DATATABLES ==========
        initDataTables: function () {
            if ($.fn.DataTable) {
                $.extend(true, $.fn.dataTable.defaults, {
                    pageLength: 20,
                    responsive: true,
                    dom: '<"d-flex justify-content-between align-items-center mb-3"lf>t<"d-flex justify-content-between align-items-center mt-3"ip>',
                    language: {
                        search: "",
                        searchPlaceholder: "Search records...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ records",
                        emptyTable: '<div class="empty-state py-4"><i class="bi bi-inbox empty-state-icon"></i><p class="empty-state-title">No data available</p></div>',
                        paginate: {
                            next: '<i class="bi bi-chevron-right"></i>',
                            previous: '<i class="bi bi-chevron-left"></i>'
                        }
                    }
                });

                // Initialize all data tables
                $('.data-table').each(function () {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable();
                    }
                });
            }
        },

        // ========== FORM VALIDATION ==========
        initFormValidation: function () {
            // Bootstrap 5 validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Mark required fields
            $('input[required], select[required], textarea[required]').each(function () {
                const label = $('label[for="' + $(this).attr('id') + '"]');
                if (label.length && !label.hasClass('required')) {
                    label.addClass('required');
                }
            });
        },

        // ========== DELETE CONFIRMATION ==========
        initDeleteConfirmation: function () {
            // Handle delete buttons with data attributes
            $(document).on('click', '[data-delete]', function (e) {
                e.preventDefault();
                const id = $(this).data('delete');
                const action = $(this).data('action') || $(this).attr('href');
                const message = $(this).data('message') || 'Are you sure you want to delete this item?';

                if (typeof confirmDelete === 'function') {
                    confirmDelete(id, action, message);
                } else {
                    if (confirm(message)) {
                        window.location.href = action;
                    }
                }
            });
        },

        // ========== TOOLTIPS & POPOVERS ==========
        initTooltips: function () {
            // Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Bootstrap popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(el => new bootstrap.Popover(el));
        },

        // ========== LOADING STATES ==========
        initLoadingStates: function () {
            // Add loading state to forms on submit
            $('form').on('submit', function () {
                const btn = $(this).find('button[type="submit"]');
                if (btn.length && !btn.hasClass('no-loading')) {
                    btn.addClass('btn-loading').prop('disabled', true);
                    btn.data('original-text', btn.html());
                }
            });

            // Global AJAX loading indicator
            $(document).ajaxStart(function () {
                $('body').addClass('ajax-loading');
            }).ajaxStop(function () {
                $('body').removeClass('ajax-loading');
            });
        }
    };

    // ========== TOAST NOTIFICATIONS ==========
    window.showToast = function (message, type = 'info', duration = 5000) {
        const container = document.querySelector('.toast-container');
        if (!container) {
            console.warn('Toast container not found');
            return;
        }

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
            <div id="${toastId}" class="toast align-items-center text-white ${bgColors[type]} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi ${icons[type]}"></i>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHTML);
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl, { delay: duration });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    };

    // Convenience functions
    window.toastSuccess = (msg) => showToast(msg, 'success');
    window.toastError = (msg) => showToast(msg, 'error');
    window.toastWarning = (msg) => showToast(msg, 'warning');
    window.toastInfo = (msg) => showToast(msg, 'info');

    // ========== AJAX HELPERS ==========
    window.ajaxPost = function (url, data, callbacks = {}) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (callbacks.onSuccess) callbacks.onSuccess(response);
                    if (response.message) toastSuccess(response.message);
                } else {
                    if (callbacks.onError) callbacks.onError(response);
                    toastError(response.message || 'An error occurred');
                }
            },
            error: function (xhr) {
                if (callbacks.onError) callbacks.onError(xhr);
                toastError('Request failed. Please try again.');
            }
        });
    };

    // ========== CONFIRMATION MODAL ==========
    window.confirmDelete = function (id, action, message = null) {
        const modal = document.getElementById('deleteModal');
        if (!modal) return;

        const form = document.getElementById('deleteForm');
        const itemId = document.getElementById('deleteItemId');
        const msgEl = document.getElementById('deleteModalMessage');

        if (itemId) itemId.value = id;
        if (form) form.action = action;
        if (msgEl) msgEl.textContent = message || 'Are you sure you want to delete this item? This action cannot be undone.';

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    };

    // ========== UTILITIES ==========
    window.formatCurrency = function (amount, currency = 'GHS') {
        return new Intl.NumberFormat('en-GH', {
            style: 'currency',
            currency: currency
        }).format(amount);
    };

    window.formatDate = function (date, format = 'medium') {
        const options = {
            short: { month: 'short', day: 'numeric' },
            medium: { year: 'numeric', month: 'short', day: 'numeric' },
            long: { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' }
        };
        return new Date(date).toLocaleDateString('en-GB', options[format] || options.medium);
    };

    // ========== INITIALIZE ==========
    $(document).ready(function () {
        App.init();
    });

})(jQuery);
