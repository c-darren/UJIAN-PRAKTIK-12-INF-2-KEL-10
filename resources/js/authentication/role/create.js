function clickCreateRoleModalButton() {
    document.getElementById('showCreateModal').click();
}
document.addEventListener('DOMContentLoaded', function () {
    
    const createRoleForm = document.getElementById('createRoleForm');
    
    if (createRoleForm) {
        createRoleForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});
document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.create-role-btn')) {
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
        // Fungsi untuk mereset form
        resetCreateForm() {
            const createRoleForm = this.$refs.createRoleForm;
            if (createRoleForm) {
                createRoleForm.reset();
            }
        },
        submitCreateForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const createRoleForm = this.$refs.createRoleForm;
            if (!createRoleForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

                // Ambil nilai dari elemen form melalui x-ref
                const roleName = createRoleForm.querySelector('[name="roleName"]').value;
                const desc = createRoleForm.querySelector('[name="desc"]').value;

            // Validate empty inputs
            if (!roleName || !desc) {
                let missingFields = [];

                if (!roleName) missingFields.push('Role Name');
                if (!desc) missingFields.push('Description');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(createRoleForm);
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
                    Notiflix.Report.success('Success', data.message, 'OK');
                    Alpine.store('createModal').close();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while creating the role.');
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
    };
}