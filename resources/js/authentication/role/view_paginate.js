$(document).ready(function () {
    const dataTable = new simpleDatatables.DataTable("#roles_table", {
        searchable: true,
        sortable : true,
        paging: true,
        perPage: 5,
        perPageSelect: [5, 10, 15, 20, 25],
    });
    $(".datatable-top").addClass("w-full mb-1 px-5");
    $(".datatable-bottom").addClass("w-full mb-1 px-5");
});

// document.addEventListener('DOMContentLoaded', function() {
//     const paginateDiv = document.querySelector('.paginate_custom');

//     if (paginateDiv) {
//         const allElements = paginateDiv.querySelectorAll('*');

//         allElements.forEach(element => {
//             const hasAllClasses = 
//                 element.classList.contains('text-sm') &&
//                 element.classList.contains('text-gray-700') &&
//                 element.classList.contains('leading-5') &&
//                 element.classList.contains('dark:text-gray-400');

//             const hasOnlyTheseClasses = 
//                 hasAllClasses && 
//                 element.classList.length === 4;

//             if (hasOnlyTheseClasses) {
//                 element.classList.add('hidden');
//             }
//         });
//     }
// });
