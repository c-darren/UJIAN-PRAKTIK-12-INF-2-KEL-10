document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-material-btn')) {
        const button = event.target;

        const tableData = {
            col_01: button.getAttribute('data-col_01'),
            actionUrl: button.getAttribute('data-actionUrl'),
        };

        Alpine.store('deleteMaterialModal').show(tableData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteMaterialModal', {
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

function deleteMaterialModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {
            const searchButton = document.getElementById('search-button');
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
                    Notiflix.Notify.success('Beerhasil menghapus.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteMaterialModal').close();
                    searchButton.click();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Report.failure('Failed', error.message, 'OK');
            })
            .finally(() => {
                window.setTimeout(() => {
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}