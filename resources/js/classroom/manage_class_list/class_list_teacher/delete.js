document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-button');
    const searchButton = document.getElementById('search-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const actionUrl = this.getAttribute('data-actionUrl');
            const teacherName = this.getAttribute('data-name');
            Notiflix.Confirm.show(
                'Delete Confirmation',
                'Are you sure you want to delete ' + teacherName + '?',
                'Yes',
                'No',
                function ok() {
                    axios.delete(actionUrl, {
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
                            searchButton.click();
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
