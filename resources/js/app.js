import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

window.confirmAction = function (options = {}) {
    return Swal.fire({
        title: options.title ?? 'Are you sure?',
        text: options.text ?? 'This action cannot be undone.',
        icon: options.icon ?? 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmButtonText ?? 'Yes',
        cancelButtonText: options.cancelButtonText ?? 'Cancel',
        confirmButtonColor: options.confirmButtonColor ?? '#dc3545',
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const toastEl = document.getElementById('app-toast');

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
                title: form.dataset.confirmTitle || 'Are you sure?',
                text: form.dataset.confirmText || 'This action cannot be undone.',
                confirmButtonText: 'Delete',
            });

            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
