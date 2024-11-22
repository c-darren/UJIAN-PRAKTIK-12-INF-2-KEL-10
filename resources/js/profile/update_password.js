(() => {
    const form = $('#password-reset-form');
    let isButtonCooldown = false;

    form.on('submit', async function (event) {
        event.preventDefault();

        // Cek apakah sedang dalam cooldown
        if (isButtonCooldown) return;

        if (!validateForm()) {
            return;
        }

        const formData = form.serialize();
        const submitButton = form.find('button[type="submit"]');

        // Menonaktifkan tombol dan menampilkan indikator loading
        submitButton.prop('disabled', true);
        isButtonCooldown = true;
        const originalButtonText = submitButton.html();

        try {
            const response = await axios.post(form.attr('action'), formData, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                }
            });

            const data = response.data;

            if (data.success) {
                Notiflix.Notify.success(data.message || 'Password reset link sent successfully.');
            } else {
                Notiflix.Notify.failure(data.message || 'Failed to send password reset link.');
            }
        } catch (error) {
            console.error('Error:', error);

            let errorData = error.response?.data;
            let secondsLeft = 60; // Default cooldown

            // Ekstrak sisa waktu dari response error
            if (errorData && typeof errorData.secondsLeft === 'number') {
                secondsLeft = Math.ceil(errorData.secondsLeft);
            }

            // Tampilkan pesan error
            if (errorData && errorData.errors) {
                Object.values(errorData.errors).forEach(errors => {
                    errors.forEach(errorMessage => {
                        Notiflix.Notify.failure(errorMessage);
                    });
                });
            } else {
                const errorMessage = errorData && errorData.message 
                    ? errorData.message 
                    : 'An error occurred. Please try again.';
                Notiflix.Notify.failure(errorMessage);
            }

            // Mulai countdown
            startButtonCountdown(submitButton, secondsLeft, originalButtonText);
            return;
        }

        // Reset button di akhir proses
        submitButton.prop('disabled', false);
        submitButton.html(originalButtonText);
        isButtonCooldown = false;
    });

    function startButtonCountdown(button, seconds, originalText) {
        let countdownValue = Math.ceil(seconds);
        button.prop('disabled', true);
        
        const updateButtonText = () => {
            button.html(`Please wait ${countdownValue}s`);
        };

        updateButtonText();

        const countdownInterval = setInterval(() => {
            countdownValue -= 1;
            
            if (countdownValue > 0) {
                updateButtonText();
            } else {
                clearInterval(countdownInterval);
                button.prop('disabled', false);
                button.html(originalText);
                isButtonCooldown = false;
            }
        }, 1000);
    }

    function validateForm() {
        const emailInput = $('#email');
        const email = emailInput.val().trim();
        let isValid = true;

        // Reset error
        emailInput.removeClass('input-error');

        // Validasi Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            showError(emailInput, 'Email is required.');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError(emailInput, 'Please enter a valid email address.');
            isValid = false;
        }

        return isValid;
    }

    function showError(inputElement, message) {
        inputElement.addClass('input-error');
        Notiflix.Notify.failure(message);
    }
})();