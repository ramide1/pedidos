import Swal from 'sweetalert2';
import { TabulatorFull as Tabulator } from 'tabulator-tables';

window.Swal = Swal;
window.Tabulator = Tabulator;

window.confirmDelete = (form) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
};

window.confirmMarkAsPaid = (form) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Marcar como pagado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, marcar como pagado',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
};

document.addEventListener('livewire:navigated', () => {
    const successMeta = document.querySelector('meta[name="session-success"]');
    const errorMeta = document.querySelector('meta[name="session-error"]');
    if (successMeta) {
        Swal.fire({
            title: '¡Éxito!',
            text: successMeta.getAttribute('content'),
            icon: 'success',
            timer: 3000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timerProgressBar: true,
        });
    } else if (errorMeta) {
        Swal.fire({
            title: 'Error',
            text: errorMeta.getAttribute('content'),
            icon: 'error',
            timer: 5000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timerProgressBar: true,
        });
    }
});