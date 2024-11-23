document.addEventListener('DOMContentLoaded', function () {
    const deleteRoleForm = document.getElementById('deleteRoleForm');
    
    if (deleteRoleForm) {
        deleteRoleForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.edit-role-btn')) {
        const button = event.target;
        const roleData = {
            id: button.getAttribute('data-id'),
            role_name: button.getAttribute('data-role_name'),
            desc: button.getAttribute('data-desc'),
        };

        Alpine.store('editModal').show(roleData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('editModal', {
        open: false,
        role: {},
        show(roleData) {
            this.role = roleData;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});

function editModalData() {
    return {
        isSubmitting: false,
        submitEditForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const editRoleForm = this.$refs.editRoleForm;
            if (!editRoleForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const role_id = editRoleForm.elements['id'].value;
            const roleName = editRoleForm.elements['roleName'].value;
            const desc = editRoleForm.elements['roleDesc'].value;

            // Validate empty inputs
            if (!role_id || !roleName || !desc) {
                let missingFields = [];

                if (!role_id) missingFields.push('Role ID');
                if (!roleName) missingFields.push('Role Name');
                if (!desc) missingFields.push('Description');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(editRoleForm);
            formData.append('_method', 'PUT');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/edit/${role_id}`;
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
                    Notiflix.Notify.success('Role has been successfully updated.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('editModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while updating the role.');
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
    };
}