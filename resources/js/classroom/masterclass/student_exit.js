document.addEventListener('DOMContentLoaded', function () {
    const exitModal = document.getElementById('exitModal');
    // const exitForm = document.getElementById('exitForm');

    if (exitModal) {
        exitModal.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target && event.target.matches('.exit-data-btn')) {
            const button = event.target;
            const data = {
                id: button.getAttribute('data-id'),
            };

            Alpine.store('exitModal').show(data);
        }
    });

    document.addEventListener('alpine:init', () => {
        Alpine.store('exitModal', {
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

function exitModalData() {
    return {
        isSubmitting: false,
        submitExitForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const exitForm = this.$refs.exitForm;
            const actionUrl = exitForm.getAttribute('action');

            const formData = new FormData(exitForm);
            formData.append('_method', 'PUT');
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
                    Notiflix.Notify.success('Student exited successfully.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1500);
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure(error.message);
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
                Alpine.store('exitModal').close();
            });
        }
    };
}