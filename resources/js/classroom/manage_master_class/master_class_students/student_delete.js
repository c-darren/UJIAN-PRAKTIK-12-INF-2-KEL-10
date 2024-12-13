document.addEventListener('DOMContentLoaded', function () {
    Notiflix.Notify.init({
        width: '280px',
        height: '100px',
        distance: '10px',
    });
    const deleteModal = document.getElementById('deleteModal');
    // const deleteForm = document.getElementById('deleteForm');

    if (deleteModal) {
        deleteModal.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target && event.target.matches('.delete-data-btn')) {
            const button = event.target;
            const data = {
                action: button.getAttribute('data-action'),
                col_01: button.getAttribute('data-col_01'),
            };

            Alpine.store('deleteModal').show(data);
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
            }
        });
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const deleteForm = this.$refs.deleteForm;
            const actionUrl = this.$store.deleteModal.data.action;

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
                    Notiflix.Notify.success('Student deleted successfully.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure(error.message);
            })
            .finally(() => {
                this.isSubmitting = false;
                Alpine.store('deleteModal').close();
                document.getElementById('search-button').click();
            });
        }
    };
}