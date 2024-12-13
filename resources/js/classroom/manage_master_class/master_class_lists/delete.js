document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const classListId = this.getAttribute('data-id');

            Notiflix.Confirm.show(
                'Delete Confirmation',
                'Are you sure you want to delete this class list?',
                'Yes',
                'No',
                function ok() {
                    axios.delete(`/classroom/masterClass/manage/${window.masterClassId}/class_lists/delete/${classListId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            Notiflix.Notify.success(response.data.message, {
                                timeout: 2000,
                                clickToClose: true,
                            });
                            // Reload halaman atau hapus row dari tabel secara dinamis
                            window.location.reload();
                        } else {
                            Notiflix.Report.failure('Failed to Delete', response.data.message, 'OK');
                        }
                    })
                    .catch(error => {
                        if (error.response && error.response.data && error.response.data.message) {
                            Notiflix.Report.failure('Failed to Delete', error.response.data.message, 'OK');
                        } else {
                            Notiflix.Report.failure('Failed to Delete', 'An error occurred while deleting the class list.', 'OK');
                        }
                    });
                },
                function cancel() {
                    // User canceled the deletion
                }
            );
        });
    });
});
