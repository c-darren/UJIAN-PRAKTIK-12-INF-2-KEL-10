$(function() {
    const form = $('#password-reset-form');
    const submitButton = form.find('button[type="submit"]');
    const emailInput = form.find('input[name="email"]');
    const originalButtonText = submitButton.html();
    let isSubmitting = false;

    form.on('submit', function(event) {
        event.preventDefault();
        
        // Cegah multiple submit
        if (isSubmitting) return;

        const email = emailInput.val().trim();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Validation
        if (email === '') {
            Notiflix.Notify.failure('Email is required.');
            return;
        }

        // Check if the email is in a valid format
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            Notiflix.Notify.failure('Please enter a valid email address.');
            return;
        }

        isSubmitting = true;
        submitButton.prop('disabled', true);
        emailInput.prop('disabled', true);
        submitButton.html(`
            <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Please wait...
        `);

        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        axios.post($(this).attr('action'), {
            email: email,
            _method: 'PATCH'
        })
        .then(function (response) {
            Notiflix.Notify.success(
                response.data.message || 'Reset password link has sent to ' + email + '!'
            );
            // Mulai hitungan mundur 60 detik
            startButtonCountdown(60);
        })
        .catch(function (error) {
            let secondsLeft = 60;
            let errorMessage = 'Something went wrong. Please try again.';
        
            if (error.response && error.response.data) {
                const errorData = error.response.data;
                
                if (errorData.errors && errorData.errors.email) {
                    errorMessage = errorData.errors.email[0];
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
        emailInput.prop('disabled', true);

        const countdownInterval = setInterval(() => {
            countdownValue -= 1;
            
            if (countdownValue > 0) {
                updateButtonText();
            } else {
                clearInterval(countdownInterval);
                submitButton.prop('disabled', false);
                emailInput.prop('disabled', false);
                submitButton.html(originalButtonText);
                isSubmitting = false;
            }
        }, 1000);
    }
});