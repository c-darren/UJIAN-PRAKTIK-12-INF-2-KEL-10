document.addEventListener('DOMContentLoaded', function () {
    const editRoleForm = document.getElementById('editRoleForm');
    isSubmitting = false;

    editRoleForm.addEventListener('submit', function(e) {
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

        const formData = new FormData(editRoleForm);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(editRoleForm.getAttribute('action'), {
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
                    'Successfully updated the role',
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
                    'Failed to update role',
                    data.message,
                    'Okay'
                );
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('Error when processing update role.');
        })
        .finally(() => {
            isSubmitting = false;
            document.getElementById('submit_form').disabled = false;
        });
    });
});
