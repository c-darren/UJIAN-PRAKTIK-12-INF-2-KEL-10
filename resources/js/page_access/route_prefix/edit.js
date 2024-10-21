document.addEventListener('DOMContentLoaded', function () {
    const addRoutePrefixForm = document.getElementById('addRoutePrefixForm');
    isSubmitting = false;

    addRoutePrefixForm.addEventListener('submit', function(e) {
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

        const formData = new FormData(addRoutePrefixForm);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(addRoutePrefixForm.getAttribute('action'), {
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
                    'Successfully updated route prefix',
                    data.message,
                    'Okay',
                    initNotiflixTheme()
                );

                window.setTimeout(function() {
                    if(data.redirectUrl !== null) {
                        window.location.replace(data.redirectUrl);
                    }
                }, 2000);


            } else {
                Notiflix.Report.failure(
                    'Failed to create route prefix',
                    data.message,
                    'Okay'
                );
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('Error when processing update route prefix.');
        })
        .finally(() => {
            isSubmitting = false;
            document.getElementById('submit_form').disabled = false;
        });
    });
});
