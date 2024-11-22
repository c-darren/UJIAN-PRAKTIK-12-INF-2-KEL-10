$(function() {
    const form = $('#input-password-reset-form');
    const submitButton = form.find('button[type="submit"]');
    const emailInput = form.find('input[name="email"]');
    const passwordInput = form.find('input[name="password"]');
    const passwordConfirmationInput = form.find('input[name="password_confirmation"]');
    const originalButtonText = submitButton.html();
    let isSubmitting = false;

    // Fungsi untuk toggle password visibility
    function togglePasswordVisibility(passwordField, toggleButton, eyeIcon, eyePath) {
        toggleButton.on('click', function() {
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            // Mengubah ikon mata
            if (type === 'password') {
                eyePath.attr('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
            } else {
                eyePath.attr('d', 'M3 3l18 18');
            }
        });
    }

    // Inisialisasi toggle untuk password
    togglePasswordVisibility(
        passwordInput,
        $('#togglePassword'),
        $('#eyeIcon'),
        $('#eyePath')
    );

    // Inisialisasi toggle untuk password konfirmasi
    togglePasswordVisibility(
        passwordConfirmationInput,
        $('#togglePasswordConfirmation'),
        $('#eyeIconConfirmation'),
        $('#eyePathConfirmation')
    );
    axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

    form.on('submit', function(event) {
        event.preventDefault();

        // Cegah multiple submit
        if (isSubmitting) return;

        const email = emailInput.val().trim();
        const password = passwordInput.val();
        const passwordConfirmation = passwordConfirmationInput.val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const actionUrl = form.attr('action');

        // Validasi di sisi frontend
        const errors = [];

        if (email !== '') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                errors.push('Please enter a valid email address.');
            }
        }

        if (password !== '') {
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!passwordPattern.test(password)) {
                errors.push('Password consists of at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.');
            }
        }

        if (password !== passwordConfirmation) {
            errors.push('Password confirmation does not match.');
        }

        if (errors.length > 0) {
            Notiflix.Notify.failure(errors.join('<br>'));
            return;
        }

        isSubmitting = true;
        submitButton.prop('disabled', true);
        emailInput.prop('readonly', true);
        passwordInput.prop('readonly', true);
        passwordConfirmationInput.prop('readonly', true);
        submitButton.html(`
            <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Please wait...
        `);

        axios.post(actionUrl, {
            email: email,
            password: password,
            password_confirmation: passwordConfirmation,
            _method: 'PATCH',
            token: $('#token').val()
        })
        .then(function (response) {
            Notiflix.Notify.success(
                response.data.message || 'Your password has been reset successfully!'
            );
            window.setTimeout(() => {
                window.location.href = `/${response.data.redirect}`;
            }, 2000);
        })
        .catch(function (error) {
            let secondsLeft = 60;
            let errorMessage = 'There was an error. Please try again.';

            if (error.response && error.response.data) {
                const errorData = error.response.data;
                
                if (errorData.errors) {
                    const errorMessages = [];
                    for (const key in errorData.errors) {
                        if (errorData.errors.hasOwnProperty(key)) {
                            errorMessages.push(errorData.errors[key].join(' '));
                        }
                    }
                    errorMessage = errorMessages.join('<br>');
                } else if (errorData.message) {
                    errorMessage = errorData.message;
                }

                if (typeof errorData.secondsLeft === 'number') {
                    secondsLeft = Math.ceil(errorData.secondsLeft);
                }
            }
            Notiflix.Notify.failure(errorMessage);
            startButtonCountdown(secondsLeft);
        });
    });

    function startButtonCountdown(seconds) {
        let countdownValue = Math.ceil(seconds);
        
        const updateButtonText = () => {
            submitButton.html(`
                <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Please wait ${countdownValue} seconds
            `);
        };

        updateButtonText();
        submitButton.prop('disabled', true);

        const countdownInterval = setInterval(() => {
            countdownValue -= 1;
            
            if (countdownValue > 0) {
                updateButtonText();
            } else {
                clearInterval(countdownInterval);
                submitButton.prop('disabled', false);
                submitButton.html(originalButtonText);
                isSubmitting = false;
                // Mengaktifkan kembali input fields
                emailInput.prop('readonly', false);
                passwordInput.prop('readonly', false);
                passwordConfirmationInput.prop('readonly', false);
            }
        }, 1000);
    }
});
