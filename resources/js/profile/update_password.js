$(() => {
    const form = $('#password-update-form');
    const togglePasswordClass = '.toggle-password';

    // Regex untuk validasi password
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    // Toggle Password Visibility
    function togglePasswordVisibility() {
        $(togglePasswordClass).on('click', function () {
            const $button = $(this);
            const $targetInput = $($button.data('target'));

            // Toggle tipe input
            const isPassword = $targetInput.attr('type') === 'password';
            $targetInput.attr('type', isPassword ? 'text' : 'password');
        });
    }

    // Validasi Form
    function validateForm() {
        const currentPassword = $('#current_password').val();
        const password = $('#password').val().trim();
        const confirmPassword = $('#password_confirmation').val().trim();
        let isValid = true;

        if (!currentPassword) {
            Notiflix.Notify.failure('Current password is required.');
            isValid = false;
        }

        if (!password) {
            Notiflix.Notify.failure('Password is required.');
            isValid = false;
        } else if (!passwordRegex.test(password)) {
            Notiflix.Notify.failure(
                'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.'
            );
            isValid = false;
        }

        if (password !== confirmPassword) {
            Notiflix.Notify.failure('Password confirmation does not match.');
            isValid = false;
        }

        return isValid;
    }

    // Submit Form
    form.on('submit', async function (event) {
        event.preventDefault();

        if (!validateForm()) return;

        const formData = form.serialize();
        const submitButton = form.find('button[type="submit"]');
        const originalButtonText = submitButton.html();

        submitButton.prop('disabled', true).text('Submitting...');

        try {
            const response = await axios.post(form.attr('action'), formData, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });

            if (response.data.success) {
                Notiflix.Notify.success(response.data.message || 'Password changed successfully.');
                Notiflix.Notify.info('You need to log in again.');
        
                setTimeout(() => {
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/logout',
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        success: function() {
                            window.location.href = '/login';
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 419) {
                                Notiflix.Notify.failure(csrfErrorMessage);
                                location.reload();
                            } else {
                                Notiflix.Notify.failure('An unexpected error occurred.');
                            }
                        }
                    });
                }, 4000);
            } else {
                Notiflix.Notify.failure(response.data.message || 'Failed to change password.');
            }
        } catch (error) {
            const errorMessage = error.response?.data?.message || 'An error occurred. Please try again.';
            Notiflix.Notify.failure(errorMessage);
        } finally {
            submitButton.prop('disabled', false).html(originalButtonText);
        }
    });

    // Inisialisasi Toggle Password Visibility
    togglePasswordVisibility();
});
