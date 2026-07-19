import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

window.confirmAction = function (options = {}) {
    const i18n = window.appI18n || {};

    return Swal.fire({
        title: options.title ?? i18n.areYouSure ?? 'Are you sure?',
        text: options.text ?? i18n.cannotUndo ?? 'This action cannot be undone.',
        icon: options.icon ?? 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmButtonText ?? i18n.delete ?? i18n.yes ?? 'Yes',
        cancelButtonText: options.cancelButtonText ?? i18n.cancel ?? 'Cancel',
        confirmButtonColor: options.confirmButtonColor ?? '#0f766e',
    });
};

function animateCounters() {
    document.querySelectorAll('[data-count-to]').forEach((el) => {
        const target = Number(el.getAttribute('data-count-to') || 0);
        const decimals = Number(el.getAttribute('data-count-decimals') || 0);
        const duration = 900;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = target * eased;
            el.textContent = decimals > 0 ? value.toFixed(decimals) : Math.round(value).toString();
            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    });
}

function animateProgressBars() {
    document.querySelectorAll('.progress-modern [data-progress-width]').forEach((bar) => {
        const width = bar.getAttribute('data-progress-width') || '0';
        requestAnimationFrame(() => {
            bar.style.width = `${width}%`;
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const toastEl = document.getElementById('app-toast');
    const i18n = window.appI18n || {};

    if (toastEl && window.bootstrap?.Toast) {
        const toast = window.bootstrap.Toast.getOrCreateInstance(toastEl);
        toast.show();
    }

    document.querySelectorAll('form[data-loading="true"]').forEach((form) => {
        form.addEventListener('submit', () => {
            const loader = document.getElementById('app-loading');
            if (loader) {
                loader.classList.remove('d-none');
            }
        });
    });

    document.querySelectorAll('form[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const result = await window.confirmAction({
                title: form.dataset.confirmTitle || i18n.areYouSure,
                text: form.dataset.confirmText || i18n.cannotUndo,
                confirmButtonText: i18n.delete,
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    animateCounters();
    animateProgressBars();
});
