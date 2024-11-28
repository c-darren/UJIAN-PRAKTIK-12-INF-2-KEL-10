function clickCreateSubjectModalButton() {
    document.getElementById('showCreateModal').click();
}
document.addEventListener('DOMContentLoaded', function () {
    const createSubjectForm = document.getElementById('createSubjectForm');

    if (createSubjectForm) {
        createSubjectForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});
document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.create-subject-btn')) {
        Alpine.store('createModal').show();
    }
});

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
            const createSubjectForm = this.$refs.createSubjectForm;  // Referensi form
            if (createSubjectForm) {
                createSubjectForm.reset();
            }
        },
        submitCreateForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const createSubjectForm = this.$refs.createSubjectForm;  // Menggunakan x-ref yang benar
            if (!createSubjectForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }
        
            const subject = createSubjectForm.querySelector('[name="subject_name"]').value; // Ambil value dari input
            
            // Validasi jika field kosong
            if (!subject) {
                Notiflix.Notify.failure('The following fields are required: Subject Name');
                this.isSubmitting = false;
                return;
            }
        
            const formData = new FormData(createSubjectForm);
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
                    Notiflix.Notify.success('Subject has been successfully created.', {
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
