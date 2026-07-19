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
        confirmButtonColor: options.confirmButtonColor ?? '#dc3545',
    });
};

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
});
