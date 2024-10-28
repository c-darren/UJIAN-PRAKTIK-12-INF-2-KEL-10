document.addEventListener('DOMContentLoaded', function () {
    const redirectUrl = '{{ $redirectUrl }}';

    const addRolesForm = document.getElementById('addRolesForm');
    isSubmitting = false;

    addRolesForm.addEventListener('submit', function(e) {
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

        document.getElementById('submit_form').disabled = true;

        const formData = new FormData(addRolesForm);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(addRolesForm.getAttribute('action'), {
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
                    'Successfully created role',
                    data.message,
                    'Okay',
                    initNotiflixTheme() // Menggunakan tema yang sudah diatur
                );

                window.setTimeout(function() {
                    if (window.redirectUrl) {
                        window.location.replace(window.redirectUrl);
                    }
                }, 2000);

            } else {
                Notiflix.Report.failure(
                    'Failed to create role',
                    data.message,
                    'Okay'
                );
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('Error when processing create role.');
        })
        .finally(() => {
            isSubmitting = false;
            document.getElementById('submit_form').disabled = false;
        });
    });
});
