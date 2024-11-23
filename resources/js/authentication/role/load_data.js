document.addEventListener("DOMContentLoaded", function() {
    // Fungsi untuk memuat dan memperbarui data tabel
    function loadData() {
        fetch("view/data")
            .then(response => response.json())  // Mengambil data dalam format JSON
            .then(data => {
                // Memasukkan data ke dalam tabel
                const tbody = document.querySelector("#roles_table tbody");
                tbody.innerHTML = '';  // Hapus isi tabel lama sebelum memuat yang baru

                // Cek apakah data yang diterima adalah array
                if (Array.isArray(data)) {
                    // Loop data dan menambahkannya ke tabel
                    data.forEach(item => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 break-all">${item.id}</td>
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 break-all">${item.role}</td>
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 break-all">${item.description}</td>
                            <td class="p-4 space-x-2 whitespace-nowrap">
                                <button class="read-more-role-btn text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:focus:ring-blue-900" data-id="${item.id}" data-role_name="${item.role}" data-desc="${item.description}">
                                    Read More
                                </button>
                                <button class="edit-role-btn text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:focus:ring-blue-900" data-id="${item.id}" data-role_name="${item.role}" data-desc="${item.description}">
                                    Edit
                                </button>
                                <button class="delete-role-btn text-white bg-red-600 hover:bg-red-650 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:focus:ring-blue-900" data-id="${item.id}" data-role_name="${item.role}">
                                    Delete
                                </button>
                            </td>
                        `;
                    });

                    // Setelah data dimasukkan, inisialisasi DataTable hanya jika belum ada
                    if (!window.dataTableInitialized) {
                        new simpleDatatables.DataTable("#roles_table", {
                            searchable: true,
                            sortable: true,
                            paging: true,
                            perPage: 5,
                            perPageSelect: [5, 10, 15, 20, 25],
                            labels: {
                                placeholder: "Search...",
                                perPage: "entries per page",
                                noRows: "Loading...",
                                info: "Showing {start} to {end} of {rows} entries"
                            },
                            allowHTML: true // Mengizinkan HTML dalam sel tabel
                        });
                    
                        // Tambahkan class styling jika diperlukan
                        $(".datatable-top").addClass("w-full mb-1 px-5");
                        $(".datatable-bottom").addClass("w-full mb-1 px-5");

                        // Tandai bahwa DataTable sudah diinisialisasi
                        window.dataTableInitialized = true;
                    }
                } else {
                    console.error("Data yang diterima tidak dalam format array.");
                }
            })
            .catch(error => {
                console.error("Error loading data: ", error);
            });
    }

    // Panggil fungsi loadData() untuk pertama kali saat halaman dimuat
    loadData();

    // Set interval untuk memperbarui data setiap 8-10 detik (misalnya 9000ms)
    setInterval(loadData, 9000); // Setiap 9 detik untuk pembaruan otomatis
});
