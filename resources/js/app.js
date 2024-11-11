
import 'bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

import Swal from 'sweetalert2';
window.Swal = Swal;

window.confirmDelete = function(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $wire.call('delete', studentId)
        }
    });
};
