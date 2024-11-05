document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-role-btn')) {
        const button = event.target;
        const roleData = {
            id: button.getAttribute('data-id'),
            role_name: button.getAttribute('data-role_name'),
        };

        Alpine.store('deleteModal').show(roleData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteModal', {
        open: false,
        role: {},
        show(roleData) {
            this.role = roleData;
            this.open = true;
        },
        close() {
            this.open = false;
            this.role = {}; // Reset role data saat modal ditutup
        }
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {

            const deleteRoleForm = this.$refs.deleteRoleForm;
            if (!deleteRoleForm) {
                console.error('Form Not Found');
                return;
            }
            //Diambil berdasarkan name pada input
            const role_id = deleteRoleForm.elements['roleId'].value;
            const roleName = deleteRoleForm.elements['roleName'].value;
            const originalroleName = deleteRoleForm.elements['originalroleName'].value;

            // Validasi input yang kosong
            if (!role_id || !roleName) {
                let missingFields = [];

                if (!role_id) missingFields.push('Role ID');
                if (!roleName) missingFields.push('Role Name');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }

            if(roleName !== originalroleName) {
                Notiflix.Notify.failure(`The role name must match.`);
                return;
            }

            const formData = new FormData(deleteRoleForm);
            formData.append('_method', 'DELETE');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/delete/${role_id}`;
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
                    Alpine.store('deleteModal').close();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the role.');
                console.error('Error:', error);
            })
            .finally(() => {
            });
        }
    };
}
