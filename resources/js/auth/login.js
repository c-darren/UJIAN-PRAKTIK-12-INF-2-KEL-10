$(() => {
    const $passwordField = $('#password');
    const $togglePasswordButton = $('#togglePassword');
    const $eyePath = $('#eyePath');
    const $loginForm = $('#loginForm');
    const $loginFormView = '#loginFormView'; // Selector untuk Notiflix Block
    let isSubmitting = false;

    // Toggle Password Visibility
    $togglePasswordButton.on('click', function () {
        const isPassword = $passwordField.attr('type') === 'password';
        $passwordField.attr('type', isPassword ? 'text' : 'password');

        // Ubah ikon mata
        $eyePath.attr('d', isPassword
            ? 'M3 3l18 18'
            : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z');
    });

    // Form Submission
    $loginForm.on('submit', function (e) {
        e.preventDefault();

        if (isSubmitting) return;
        isSubmitting = true;

        const login = $loginForm.find('input[name="login"]').val().trim();
        const password = $loginForm.find('input[name="password"]').val();

        if (!login) {
            Notiflix.Notify.failure('Email or username is required.');
            isSubmitting = false;
            return;
        }

        if (!password) {
            Notiflix.Notify.failure('Password is required.');
            isSubmitting = false;
            return;
        }

        // Kondisi untuk memeriksa tema gelap atau terang
        const isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

        Notiflix.Block.pulse($loginFormView, 'Please Wait', {
            backgroundColor: isDarkMode ? '#1f2937' : '#fff',
            color: isDarkMode ? 'white' : '#000',
            fontSize: '16px',
            borderRadius: '5px',
            messageColor: isDarkMode ? '#fff' : '#000',
        });

        // Data untuk dikirim
        const formData = $loginForm.serialize();
        const csrfToken = $('input[name="_token"]').val();

        // AJAX Request
        $.ajax({
            url: $loginForm.attr('action'),
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            success: function (data) {
                Notiflix.Block.remove($loginFormView, 3000);

                if (data.success) {
                    $('#submit').prop('disabled', true);
                    Notiflix.Report.success(
                        'Login Success!',
                        'You will be redirected to the dashboard in 2 seconds.',
                        'Okay'
                    );
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    setTimeout(() => {
                        Notiflix.Notify.failure(data.message || 'Login failed.');
                        $passwordField.val('');
                    }, 2000);
                }

                isSubmitting = false;
            },
            error: function () {
                Notiflix.Notify.failure('Error during login.');
                Notiflix.Block.remove($loginFormView, 3000);
                isSubmitting = false;
            }
        });
    });
});
