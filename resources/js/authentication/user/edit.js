document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.edit-user-btn')) {
        const button = event.target;
        const userData = {
            id: button.getAttribute('data-id'),
            name: button.getAttribute('data-name'),
            username: button.getAttribute('data-username'),
            email: button.getAttribute('data-email'),
            role_id: button.getAttribute('data-role_id'),
            avatar: button.getAttribute('data-avatar')
        };

        Alpine.store('editModal').show(userData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('editModal', {
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
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarPreview) {
                avatarPreview.src = userData.avatar;
            }

            // Reset checkbox saat modal dibuka
            this.user.resetPassword = false;
            this.user.deleteAvatar = false;
        },
        close() {
            this.open = false;
        }
    });
});


function editModalData() {
    return {
        newAvatarPreview: null,
        isSubmitting: false,
        submitEditForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            const editUserForm = this.$refs.editUserForm;
            if (!editUserForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const user_id = document.getElementById('userId').value;
            const fullName = document.getElementById('fullName').value;
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const roleId = document.getElementById('roleId').value;

            // Validasi input yang kosong
            if (!user_id || !fullName || !username || !email || !roleId) {
                let missingFields = [];

                if (!user_id) missingFields.push('ID');
                if (!fullName) missingFields.push('Full Name');
                if (!username) missingFields.push('Username');
                if (!email) missingFields.push('Email');
                if (!roleId) missingFields.push('Role');

                Notiflix.Notify.failure(`This field is required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(editUserForm);
            formData.append('_method', 'PUT');
            
            formData.append('resetPassword', document.getElementById('resetPassword').checked ? '1' : '0');
            formData.append('deleteAvatar', document.getElementById('deleteAvatar').checked ? '1' : '0');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const userId = formData.get('id');
            const actionUrl = `${baseUrl}/edit/${userId}`;
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
                    Notiflix.Report.success('Berhasil Memperbarui Pengguna', data.message, 'Oke');
                    Alpine.store('editModal').close();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Report.failure('Gagal Memperbarui Pengguna', data.message, 'Oke');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('Terjadi kesalahan saat memperbarui pengguna.');
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
    };
}

