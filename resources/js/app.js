import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;


// ===============================TOAST=============================
//types : success,warning,info,error
// ===============================TOAST=============================

class ToastManager {
    constructor() {
        this.toasts = new Map();
        this.toastId = 0;
    }

    getContainer() {
        return document.getElementById('toastContainer');
    }

    show(options = {}) {
        const container = this.getContainer();
        if (!container) return;

        const id = ++this.toastId;
        const toast = this.createToast(id, options);

        this.toasts.set(id, toast);
        container.appendChild(toast.element);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.element.classList.add('show');
        });

        // Auto dismiss
        if (options.duration !== 0 && options.duration !== false) {
            const duration = options.duration || 4000;
            toast.timer = setTimeout(() => {
                this.dismiss(id);
            }, duration);

            // Progress bar animation
            if (toast.progressBar) {
                // Force reflow
                toast.progressBar.offsetHeight;
                toast.progressBar.style.transitionDuration = `${duration}ms`;
                toast.progressBar.style.width = '100%';
            }
        }

        return id;
    }

    createToast(id, options) {
        const element = document.createElement('div');
        element.className = `toast ${options.theme || 'light'} ${options.type || ''}`;
        element.dataset.toastId = id;

        let iconSvg = this.getIcon(options.type, options.loading);

        element.innerHTML = `
                    ${iconSvg ? `<div class="toast-icon">${iconSvg}</div>` : ''}
                    <div class="toast-content">
                        ${options.title ? `<div class="toast-title">${options.title}</div>` : ''}
                        ${options.message ? `<div class="toast-message">${options.message}</div>` : ''}
                        ${options.actions ? this.createActions(options.actions, id) : ''}
                    </div>
                    ${options.closable !== false ? `<button class="toast-close" onclick="toastManager.dismiss(${id})">×</button>` : ''}
                    ${options.showProgress !== false && options.duration !== 0 ? '<div class="toast-progress"></div>' : ''}
                `;

        const progressBar = element.querySelector('.toast-progress');

        // Pause on hover
        element.addEventListener('mouseenter', () => {
            const toast = this.toasts.get(id);
            if (toast && toast.timer) {
                clearTimeout(toast.timer);
                if (progressBar) {
                    progressBar.style.animationPlayState = 'paused';
                }
            }
        });

        element.addEventListener('mouseleave', () => {
            const toast = this.toasts.get(id);
            if (toast && options.duration !== 0) {
                const remainingTime = options.duration || 4000;
                toast.timer = setTimeout(() => {
                    this.dismiss(id);
                }, remainingTime * 0.3); // Resume with reduced time

                if (progressBar) {
                    progressBar.style.animationPlayState = 'running';
                }
            }
        });

        return {element, progressBar, timer: null};
    }

    createActions(actions, toastId) {
        return `
                    <div class="toast-actions">
                        ${actions.map(action => `
                            <button class="toast-action" onclick="${action.onClick || `toastManager.dismiss(${toastId})`}">
                                ${action.label}
                            </button>
                        `).join('')}
                    </div>
                `;
    }

    getIcon(type, loading = false) {
        if (loading) {
            return '<div class="spinner"></div>';
        }

        const icons = {
            success: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            error: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            warning: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            info: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };

        return icons[type] || '';
    }

    dismiss(id) {
        const toast = this.toasts.get(id);
        if (!toast) return;

        if (toast.timer) {
            clearTimeout(toast.timer);
        }

        toast.element.classList.add('hide');

        setTimeout(() => {
            if (toast.element.parentNode) {
                toast.element.parentNode.removeChild(toast.element);
            }
            this.toasts.delete(id);
        }, 400);
    }

    update(id, options) {
        const toast = this.toasts.get(id);
        if (!toast) return;

        const content = toast.element.querySelector('.toast-content');
        if (options.title) {
            const titleEl = content.querySelector('.toast-title') || document.createElement('div');
            titleEl.className = 'toast-title';
            titleEl.textContent = options.title;
            if (!content.querySelector('.toast-title')) {
                content.insertBefore(titleEl, content.firstChild);
            }
        }

        if (options.message) {
            const messageEl = content.querySelector('.toast-message') || document.createElement('div');
            messageEl.className = 'toast-message';
            messageEl.textContent = options.message;
            if (!content.querySelector('.toast-message')) {
                content.appendChild(messageEl);
            }
        }

        if (options.type) {
            toast.element.className = `toast show ${options.theme || 'light'} ${options.type}`;
            const iconEl = toast.element.querySelector('.toast-icon');
            if (iconEl) {
                iconEl.innerHTML = this.getIcon(options.type, options.loading);
            }
        }
    }

    clear() {
        this.toasts.forEach((toast, id) => {
            this.dismiss(id);
        });
    }

    promise(promise, options = {}) {
        const loadingId = this.show({
            ...options,
            loading: true,
            duration: 0,
            title: options.loading || 'Loading...',
            theme: options.theme || 'light'
        });

        promise
            .then((result) => {
                this.update(loadingId, {
                    type: 'success',
                    title: options.success || 'Success!',
                    message: result?.message || 'Operation completed successfully',
                    loading: false
                });

                setTimeout(() => {
                    this.dismiss(loadingId);
                }, options.duration || 3000);
            })
            .catch((error) => {
                this.update(loadingId, {
                    type: 'error',
                    title: options.error || 'Error!',
                    message: error?.message || 'Something went wrong',
                    loading: false
                });

                setTimeout(() => {
                    this.dismiss(loadingId);
                }, options.duration || 4000);
            });

        return loadingId;
    }

    setPosition(position) {
        const container = this.getContainer();
        if (container) {
            container.className = `toast-container ${position}`;
        }
    }
}

// Initialize toast manager
const toastManager = new ToastManager();
window.toastManager = toastManager;

// Helper functions
function showToast(type, title, message) {
    const themeEl = document.getElementById('themeSelect');
    const durationEl = document.getElementById('durationInput');

    const theme = themeEl ? themeEl.value : 'light';
    const duration = durationEl ? parseInt(durationEl.value) : 4000;

    toastManager.show({
        type,
        title,
        message,
        theme,
        duration
    });
}

function showCustomToast() {
    const titleEl = document.getElementById('customTitle');
    const messageEl = document.getElementById('customMessage');
    const themeEl = document.getElementById('themeSelect');
    const durationEl = document.getElementById('durationInput');

    const title = titleEl ? titleEl.value : '';
    const message = messageEl ? messageEl.value : '';
    const theme = themeEl ? themeEl.value : 'light';
    const duration = durationEl ? parseInt(durationEl.value) : 4000;

    if (!title && !message) {
        showToast('warning', 'Warning', 'Please enter a title or message');
        return;
    }

    toastManager.show({
        title: title || undefined,
        message: message || undefined,
        theme,
        duration
    });
}

function showLoadingToast() {
    const themeEl = document.getElementById('themeSelect');
    const theme = themeEl ? themeEl.value : 'light';

    toastManager.show({
        title: 'Loading...',
        message: 'Please wait while we process your request',
        loading: true,
        duration: 0,
        theme
    });
}

function showPromiseToast() {
    const themeEl = document.getElementById('themeSelect');
    const theme = themeEl ? themeEl.value : 'light';

    const mockPromise = new Promise((resolve, reject) => {
        setTimeout(() => {
            Math.random() > 0.5 ? resolve({message: 'Data loaded successfully!'}) : reject({message: 'Failed to load data'});
        }, 3000);
    });

    toastManager.promise(mockPromise, {
        loading: 'Loading data...',
        success: 'Success!',
        error: 'Failed!',
        theme
    });
}

function showActionToast() {
    const themeEl = document.getElementById('themeSelect');
    const durationEl = document.getElementById('durationInput');

    const theme = themeEl ? themeEl.value : 'light';
    const duration = durationEl ? parseInt(durationEl.value) : 0;

    toastManager.show({
        title: 'Confirm Action',
        message: 'Do you want to proceed with this action?',
        theme,
        duration: duration || 0,
        actions: [
            {
                label: 'Confirm',
                onClick: `showToast('success', 'Confirmed!', 'Action was completed'); toastManager.dismiss(this.closest('.toast').dataset.toastId);`
            },
            {
                label: 'Cancel',
                onClick: `toastManager.dismiss(this.closest('.toast').dataset.toastId);`
            }
        ]
    });
}

function updatePosition() {
    const positionEl = document.getElementById('positionSelect');
    if (positionEl) {
        toastManager.setPosition(positionEl.value);
    }
}

function clearAllToasts() {
    toastManager.clear();
}

// Global SweetAlert2 Mixin
const customSwal = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-tertiary'
    },
    buttonsStyling: true,
    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
    confirmButtonColor: '#00a095',
});

// Livewire Event Listeners
function initLivewireEvents() {
    Livewire.on('confirmSwal', (data) => {
        const buttonText = data.buttonText ?? "Yes, delete it!";
        const dpText = data.dpText ?? "deleteConfirmed";
        customSwal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: buttonText,
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(dpText);
            }
        });
    });

    Livewire.on('toast', (data) => {
        const {type, title, message} = data;
        showToast(type, title, message);
    });
}

// Initialize listeners
if (window.Livewire) {
    initLivewireEvents();
} else {
    document.addEventListener('livewire:initialized', () => {
        initLivewireEvents();
    });
}

// Make functions global
window.showToast = showToast;
window.showCustomToast = showCustomToast;
window.showLoadingToast = showLoadingToast;
window.showPromiseToast = showPromiseToast;
window.showActionToast = showActionToast;
window.updatePosition = updatePosition;
window.clearAllToasts = clearAllToasts;

// Auto-demo on load
setTimeout(() => {
    // Only show if we are on a page with the demo controls, or just skip it to avoid errors on login page
    if (document.getElementById('toastContainer')) {
        showToast('error', 'Welcome! 👋', 'Try out the different toast options above');
    }
}, 1000);
// =====================TOAST===================
