$(() => {
    const $passwordField = $('#password');
    const $togglePasswordButton = $('#togglePassword');
    const $eyePath = $('#eyePath');
    const $loginForm = $('#loginForm');
    const $loginFormView = $('#loginFormView');
    let isSubmitting = false;
    const $loginInput = $loginForm.find('input[name="login"]');
    const $passwordInput = $loginForm.find('input[name="password"]');

    // Toggle password visibility
    $togglePasswordButton.on('click', function () {
        const isPassword = $passwordField.attr('type') === 'password';
        $passwordField.attr('type', isPassword ? 'text' : 'password');

        // Change eye icon
        $eyePath.attr('d', isPassword
            ? 'M3 3l18 18'
            : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z');
    });

    // Form submission
    $loginForm.on('submit', function (e) {
        e.preventDefault();

        if (isSubmitting) return;
        isSubmitting = true;

        const login = $loginInput.val().trim();
        const password = $passwordInput.val();

        if (!login) {
            Notiflix.Notify.failure('login or username is required.');
            isSubmitting = false;
            return;
        }

        if (!password) {
            Notiflix.Notify.failure('Password is required.');
            isSubmitting = false;
            return;
        }

        Notiflix.Block.pulse($loginFormView[0], 'Please Wait');

        $.ajax({
            url: $loginForm.attr('action'),
            method: 'POST',
            data: { login, password },
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                'Accept': 'application/json',
            },
            success: function (data) {
                Notiflix.Block.remove($loginFormView[0]);
                if (data.success) {
                    $('#submit').prop('disabled', true);
                    Notiflix.Report.success('Login Success!', 'Redirecting...', 'Okay');
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    Notiflix.Notify.failure(data.message || 'Login failed.');
                    $passwordField.val('');
                }
                isSubmitting = false;
            },
            error: function () {
                Notiflix.Block.remove($loginFormView[0]);
                Notiflix.Notify.failure('Error during login.');
                isSubmitting = false;
            },
        });
    });
});
