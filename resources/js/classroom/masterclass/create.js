function clickCreateModalButton() {
    document.getElementById('showCreateModal').click();
}
document.addEventListener('DOMContentLoaded', function () {
    const createForm = document.getElementById('createForm');

    if (createForm) {
        createForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});
// document.addEventListener('click', function(event) {
//     if (event.target && event.target.matches('.create-btn')) {
//         Alpine.store('createModal').show();
//     }
// });

document.addEventListener('alpine:init', () => {
    Alpine.store('createModal', {
        open: false,
        show() {
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});

function createModalData() {
    return {
        isSubmitting: false,
        resetCreateForm() {
            const createForm = this.$refs.createForm;  // Referensi form
            if (createForm) {
                createForm.reset();
            }
        },
        submitCreateForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const createForm = this.$refs.createForm;  // Menggunakan x-ref yang benar
            if (!createForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }
        
            const master_class_name = createForm.querySelector('[name="master_class_name"]').value;
            const master_class_code = createForm.querySelector('[name="master_class_code"]').value;
            const academic_year_id = createForm.querySelector('[name="academic_year_id"]').value;
            const status = createForm.querySelector('[name="status"]').value;

            let errorMessage = '';
            if (!master_class_name) {
                errorMessage += 'Master Class Name is required.\n';
            }
            if (!master_class_code) {
                errorMessage += 'Master Class Code is required.\n';
            }
            if (!academic_year_id) {
                errorMessage += 'Academic Year is required.\n';
            }
            if (!status) {
                errorMessage += 'Status is required.\n';
            }

            if (errorMessage) {
                Notiflix.Notify.failure(errorMessage);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(createForm);
            formData.append('_method', 'POST');
        
            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/store`;

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
                    Notiflix.Notify.success('Master Class successfully created.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('createModal').close();
                    this.resetCreateForm();
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
            });
        }
    };
}