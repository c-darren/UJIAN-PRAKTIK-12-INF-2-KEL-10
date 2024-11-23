document.addEventListener('DOMContentLoaded', function () {
    const signupForm = document.getElementById('signupForm');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    // Toggle password visibility
    togglePasswordButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetInput = document.querySelector(button.getAttribute('data-target'));
            const isPassword = targetInput.getAttribute('type') === 'password';
            targetInput.setAttribute('type', isPassword ? 'text' : 'password');
        });
    });

    // Avatar preview and view functionality
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const viewAvatarButton = document.getElementById('viewAvatarButton');

    // Initially hide avatar preview and view button
    avatarPreview.classList.add('hidden');
    viewAvatarButton.classList.add('hidden');

    avatarInput.addEventListener('change', function () {
        const file = avatarInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
                avatarPreview.classList.remove('hidden');
                viewAvatarButton.classList.remove('hidden');
                avatarPreview.dataset.largeImage = e.target.result; // Store image URL for large view
            }
            reader.readAsDataURL(file);
        } else {
            avatarPreview.src = '';
            avatarPreview.classList.add('hidden');
            viewAvatarButton.classList.add('hidden');
        }
    });

    // Click on avatar image to view in larger size
    avatarPreview.addEventListener('click', function () {
        const imageUrl = avatarPreview.dataset.largeImage || avatarPreview.src;
        openImageModal(imageUrl);
    });

    // Click on "View Avatar" button
    viewAvatarButton.addEventListener('click', function () {
        const imageUrl = avatarPreview.dataset.largeImage || avatarPreview.src;
        openImageModal(imageUrl);
    });

    // Function to open image modal
    function openImageModal(imageUrl) {
        // Create modal element
        const modal = document.createElement('div');
        modal.classList.add('fixed', 'inset-0', 'bg-black', 'bg-opacity-75', 'flex', 'items-center', 'justify-center', 'z-50');

        // Modal content
        const modalContent = document.createElement('div');
        modalContent.classList.add('relative');

        // Large image
        const largeImage = document.createElement('img');
        largeImage.src = imageUrl;
        largeImage.classList.add('max-w-full', 'max-h-screen');

        // Close button
        const closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;';
        closeButton.classList.add('absolute', 'top-0', 'right-0', 'text-white', 'text-3xl', 'p-2');
        closeButton.addEventListener('click', function () {
            document.body.removeChild(modal);
        });

        modalContent.appendChild(largeImage);
        modalContent.appendChild(closeButton);
        modal.appendChild(modalContent);
        document.body.appendChild(modal);
    }

    // Randomize submit button color
    const submitButton = document.getElementById('submit');
    const colors = ['bg-blue-600', 'bg-green-600', 'bg-red-600', 'bg-yellow-600', 'bg-purple-600', 'bg-pink-600', 'bg-indigo-600'];
    const randomColor = colors[Math.floor(Math.random() * colors.length)];
    submitButton.classList.add(randomColor);
    submitButton.classList.remove('bg-primary-600');

    // Form submission
    signupForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Show loading block
        const isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

        Notiflix.Block.pulse("#signupFormView", 'Please Wait', {
            backgroundColor: isDarkMode ? '#1f2937' : '#fff',
            color: isDarkMode ? 'white' : '#000',
            fontSize: '16px',
            borderRadius: '5px',
            messageColor: isDarkMode ? '#fff' : '#000',
        });

        // Prepare form data
        const formData = new FormData(signupForm);

        // Send data via axios
        axios.post(signupForm.getAttribute('action'), formData, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
        })
        .then(function (response) {
            Notiflix.Block.remove('#signupFormView');
            Notiflix.Notify.success(response.data.message || 'Registration successful.');
            // Optionally, redirect or reset form
            setTimeout(function () {
                window.location.href = response.data.redirect_url || '/login';
            }, 2000);
        })
        .catch(function (error) {
            Notiflix.Block.remove('#signupFormView');
            if (error.response && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                for (let field in errors) {
                    errors[field].forEach(function (message) {
                        Notiflix.Notify.failure(message);
                    });
                }
            } else {
                Notiflix.Notify.failure('An error occurred. Please try again.');
            }
        });
    });

    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        let isValid = true;

        // Validate name
        if (!name) {
            Notiflix.Notify.failure('Full name is required.');
            isValid = false;
        }

        // Validate username
        if (!username) {
            Notiflix.Notify.failure('Username is required.');
            isValid = false;
        }

        // Validate email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email) {
            Notiflix.Notify.failure('Email is required.');
            isValid = false;
        } else if (!emailPattern.test(email)) {
            Notiflix.Notify.failure('Please enter a valid email address.');
            isValid = false;
        }

        // Validate password
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!password) {
            Notiflix.Notify.failure('Password is required.');
            isValid = false;
        } else if (!passwordRegex.test(password)) {
            Notiflix.Notify.failure('Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.');
            isValid = false;
        }

        // Validate password confirmation
        if (password !== passwordConfirmation) {
            Notiflix.Notify.failure('Password confirmation does not match.');
            isValid = false;
        }

        return isValid;
    }
});
