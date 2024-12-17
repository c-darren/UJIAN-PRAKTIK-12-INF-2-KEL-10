document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-data-btn')) {
        const button = event.target;
        const tableData = {
            col_01: button.getAttribute('data-col_01'),
            actionUrl: button.getAttribute('data-actionUrl'),
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
            const actionUrl = deleteForm.elements['actionUrl'].value;

            if (!actionUrl) {
                let missingFields = [];

                if (!actionUrl) missingFields.push('ID');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }

            const formData = new FormData(deleteForm);
            formData.append('_method', 'DELETE');

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
                    Notiflix.Notify.success('The topic has been successfully deleted.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteModal').close();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the topic.');
            })
            .finally(() => {
                window.setTimeout(() => {
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}