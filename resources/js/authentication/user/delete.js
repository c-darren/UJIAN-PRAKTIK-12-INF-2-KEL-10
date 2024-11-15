document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-user-btn')) {
        const button = event.target;
        const userData = {
            id: button.getAttribute('data-id'),
            name: button.getAttribute('data-name'),
            username: button.getAttribute('data-username'),
            email: button.getAttribute('data-email'),
            role_name: button.getAttribute('data-role_name'),
            avatar: button.getAttribute('data-avatar')
        };

        Alpine.store('deleteModal').show(userData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteModal', {
        open: false,
        user: {},
        show(userData) {
            this.user = userData;
            this.open = true;

            // Reset input file avatar
            const avatarInput = document.querySelector('input[name="avatar"]');
            if (avatarInput) {
                avatarInput.value = '';
            }
            
            // Tampilkan preview avatar
            const deleteAvatar = document.getElementById('deleteAvatar');
            if (deleteAvatar) {
                deleteAvatar.src = userData.avatar;
            }
        },
        close() {
            this.open = false;
        }
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const deleteUserForm = this.$refs.deleteUserForm;
            if (!deleteUserForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            // Access user_id directly from Alpine store
            const user_id = this.$store.deleteModal.user.id;

            if (!user_id) {
                Notiflix.Notify.failure(`User ID is required`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(deleteUserForm);
            formData.append('_method', 'DELETE');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/delete/${user_id}`;

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
                console.log('Response Data:', data);
                if (data.success) {
                    Notiflix.Notify.success('User has been successfully deleted.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteModal').close();
                } else {
                    Notiflix.Report.failure('Failed to Delete User', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the user.');
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
    };
}


