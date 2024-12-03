document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-data-btn')) {
        const button = event.target;
        const tableData = {
            id: button.getAttribute('data-id'),
            col_01: button.getAttribute('data-col_01'),
        };

        Alpine.store('deleteModal').show(tableData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteModal', {
        open: false,
        data: {},
        show(tableData) {
            this.data = tableData;
            this.open = true;

        },
        close() {
            this.open = false;
            this.data = {}; // Reset data when modal is closed
        }
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {

            const deleteForm = this.$refs.deleteForm;
            if (!deleteForm) {
                console.error('Form Not Found');
                return;
            }
            // Get values based on input names
            const id = deleteForm.elements['id'].value;

            // Validate empty inputs
            if (!id) {
                let missingFields = [];

                if (!id) missingFields.push('ID');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }

            const formData = new FormData(deleteForm);
            formData.append('_method', 'DELETE');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/delete/${id}`;
            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    deleteForm.reset();
                    Notiflix.Notify.success('Master Class has been successfully deleted.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the Master Class.');
                console.error('Error:', error);
            })
            .finally(() => {
            });
        }
    };
}