$(document).ready(function () {
    // Inisialisasi DataTable (Baris 4-8)
    const dataTable = new simpleDatatables.DataTable("#users_table", {
        searchable: true,
        sortable: true,
        paging: true,
        perPage: 5,
        perPageSelect: [5, 10, 15, 20, 25],
    });

    // Styling pada DataTable
    $(".datatable-top").addClass("w-full mb-1 px-5");
    $(".datatable-bottom").addClass("w-full mb-1 px-5");

    // Reinitialize Alpine.js bindings setelah DataTable melakukan render ulang (Baris 12-13)
    dataTable.on('datatable.update', function () {
        // Kita tidak lagi perlu melakukan re-inisialisasi Alpine.js di sini
        // Karena kita telah menggunakan pendekatan event delegation dan global store
    });
});
