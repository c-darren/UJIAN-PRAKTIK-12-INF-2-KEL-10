document.addEventListener('DOMContentLoaded', function () {
    const rejoinModal = document.getElementById('rejoinModal');
    // const rejoinForm = document.getElementById('rejoinForm');

    if (rejoinModal) {
        rejoinModal.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target && event.target.matches('.rejoin-data-btn')) {
            const button = event.target;
            const data = {
                id: button.getAttribute('data-id'),
            };

            Alpine.store('rejoinModal').show(data);
        }
    });

    document.addEventListener('alpine:init', () => {
        Alpine.store('rejoinModal', {
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

function rejoinModalData() {
    return {
        isSubmitting: false,
        submitrejoinForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const rejoinForm = this.$refs.rejoinForm;
            const actionUrl = rejoinForm.getAttribute('action');

            const formData = new FormData(rejoinForm);
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
                    Notiflix.Notify.success('Student rejoined successfully.', {
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
                Alpine.store('rejoinModal').close();
            });
        }
    };
}