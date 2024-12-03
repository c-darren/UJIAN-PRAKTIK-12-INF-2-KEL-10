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

const resetCreateForm = () => {
    document.getElementById('createForm').reset();
};

function createModalData() {
    return {
        isSubmitting: false,
        submitCreateForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const createForm = this.$refs.createForm;
            
            if (!createForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const join_class_code = createForm.elements['join_class_code'].value;
            if (!join_class_code) {
                let missingFields = [];
        
                if (!join_class_code) missingFields.push('Class Code');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(createForm);
            formData.append('_method', 'POST');
        
            // const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = createForm.action;
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
                    Notiflix.Notify.success('Class joined successfully.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('createModal').close();
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
                setTimeout(() => {
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}