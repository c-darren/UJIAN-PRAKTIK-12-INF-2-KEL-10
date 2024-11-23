$(document).ready(function() {
    const form = $('#profile-update-form');

    form.on('submit', function(event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        const formData = new FormData(this);
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success) {
                    Notiflix.Notify.success(data.message || 'Profile updated successfully.');
        
                    if (data.message !== 'No changes made to the profile.') {
                        Notiflix.Notify.info('You need to log in again.');
        
                        setTimeout(function() {
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
                    }
                } else {
                    Notiflix.Notify.info(data.message || 'No changes made.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
        
                let errorData = xhr.responseJSON;
        
                if (errorData && errorData.errors) {
                    $.each(errorData.errors, function(field, errors) {
                        $.each(errors, function(index, errorMessage) {
                            Notiflix.Notify.failure(errorMessage);
                        });
                    });
                } else {
                    const errorMessage = errorData && errorData.message ? errorData.message : 'An error occurred. Please try again.';
                    Notiflix.Notify.failure(errorMessage);
                }
            }
        });
    });

    function validateForm() {
        let isValid = true;

        // Ambil nilai input
        const nameInput = $('#name');
        const usernameInput = $('#username');
        const emailInput = $('#email');
        const avatarInput = $('#avatar');

        const name = nameInput.val().trim();
        const username = usernameInput.val().trim();
        const email = emailInput.val().trim();
        const avatar = avatarInput[0].files[0];

        // Reset pesan error dan styling
        removeError(nameInput);
        removeError(usernameInput);
        removeError(emailInput);
        removeError(avatarInput);

        // Validasi nama
        if (name === '') {
            showError(nameInput, 'Name is required.');
            isValid = false;
        } else if (name.length > 255) {
            showError(nameInput, 'Name must not exceed 255 characters.');
            isValid = false;
        }

        // Validasi username
        const usernamePattern = /^[a-zA-Z0-9_]+$/;
        if (username === '') {
            showError(usernameInput, 'Username is required.');
            isValid = false;
        } else if (username.length > 255) {
            showError(usernameInput, 'Username must not exceed 255 characters.');
            isValid = false;
        } else if (!usernamePattern.test(username)) {
            showError(usernameInput, 'Username can only contain letters, numbers, and underscores.');
            isValid = false;
        }

        // Validasi email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            showError(emailInput, 'Email is required.');
            isValid = false;
        } else if (email.length > 255) {
            showError(emailInput, 'Email must not exceed 255 characters.');
            isValid = false;
        } else if (!emailPattern.test(email)) {
            showError(emailInput, 'Please enter a valid email address.');
            isValid = false;
        }

        // Validasi avatar (jika ada)
        if (avatar) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(avatar.type)) {
                showError(avatarInput, 'Avatar must be a JPEG, PNG, or GIF image.');
                isValid = false;
            }

            if (avatar.size > maxSize) {
                showError(avatarInput, 'Avatar must not exceed 5MB.');
                isValid = false;
            }
        }

        if (!isValid) {
            // Menggulung ke input yang bermasalah pertama
            const firstErrorInput = $('.input-error').first();
            if (firstErrorInput.length) {
                $('html, body').animate({
                    scrollTop: firstErrorInput.offset().top - 100
                }, 500);
                firstErrorInput.focus();
            }
        }

        return isValid;
    }

    function showError(inputElement, message) {
        inputElement.addClass('input-error');
        Notiflix.Notify.failure(message);
    }

    function removeError(inputElement) {
        inputElement.removeClass('input-error');
    }
});
