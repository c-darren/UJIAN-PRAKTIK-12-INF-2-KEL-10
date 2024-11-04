document.addEventListener('DOMContentLoaded', function () {

    const addUserForm = document.getElementById('addUserForm');
    let isSubmitting = false;

    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if(isSubmitting) return;
        isSubmitting = true;

        function initNotiflixTheme() {
            const isDarkTheme = localStorage.getItem('color-theme') === 'dark' || 
                                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            return {
                backgroundColor: isDarkTheme ? '#D1D9E0.' : '#ffffff',
                titleColor: isDarkTheme ? '#ffffff' : '#000',
                messageColor: isDarkTheme ? '#D1D9E0' : '#000'
            };
        }

        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const roleId = document.getElementById('role_id').value;
        const avatarInput = document.getElementById('avatar');

        if (!name) {
            Notiflix.Notify.failure('Full Name is required.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }

        if (!username) {
            Notiflix.Notify.failure('Username is required.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }

        if (!email) {
            Notiflix.Notify.failure('Email is required.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            Notiflix.Notify.failure('Invalid email format.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }

        if (!password) {
            Notiflix.Notify.failure('Password is required.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }
        if (password.length < 6) {
            Notiflix.Notify.failure('Password must be at least 6 characters long.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }
        if (password !== passwordConfirmation) {
            Notiflix.Notify.failure('Password and confirmation do not match.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }

        if (!roleId) {
            Notiflix.Notify.failure('Role must be selected.', initNotiflixTheme());
            isSubmitting = false;
            return;
        }

        if (avatarInput.files.length > 0) {
            const avatarFile = avatarInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(avatarFile.type)) {
                Notiflix.Notify.failure('Avatar must be an image file (jpeg, png, gif).', initNotiflixTheme());
                isSubmitting = false;
                return;
            }

            if (avatarFile.size > maxSize) {
                Notiflix.Notify.failure('Avatar size must not exceed 5MB.', initNotiflixTheme());
                isSubmitting = false;
                return;
            }
        }

        document.getElementById('submit_form').disabled = true;

        const formData = new FormData(addUserForm);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(addUserForm.getAttribute('action'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Notiflix.Report.success(
                    'User Created Successfully',
                    data.message,
                    'Okay',
                    initNotiflixTheme()
                );

                window.setTimeout(function() {
                    if (window.redirectUrl) {
                        window.location.replace(window.redirectUrl);
                    }
                }, 2000);

            } else {
                let errorMessage = data.message || 'Failed to create user.';
                if (data.errors) {
                    errorMessage += '\n' + Object.values(data.errors).join('\n');
                }
                Notiflix.Report.failure(
                    'Failed to Create User',
                    errorMessage,
                    'Okay',
                );
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('An error occurred while creating the user.', initNotiflixTheme());
        })
        .finally(() => {
            isSubmitting = false;
            document.getElementById('submit_form').disabled = false;
        });
    });
});
