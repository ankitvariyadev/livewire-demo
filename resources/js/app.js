
import 'bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

import Swal from 'sweetalert2';

window.confirmDeletion = function(studentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('delete', studentId); 
            Swal.fire(
                'Deleted!',
                'The record has been deleted.',
                'success'
            );
        }
    });
}

// document.addEventListener('DOMContentLoaded', function () {
//     Livewire.on('show-toast', (message) => {
//         // Show the toast and set the message
//         window.toastVisible = true;
//         window.toastMessage = message;

//         // Hide the toast after 3 seconds
//         setTimeout(() => {
//             window.toastVisible = false;
//         }, 3000);
//     });
// });
