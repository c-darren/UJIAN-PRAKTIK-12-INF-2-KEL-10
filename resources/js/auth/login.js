document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if(localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            Notiflix.Block.pulse('#loginFormView', 'Please Wait', {
                backgroundColor: '#1f2937',
                color: 'white',
                fontSize: '16px',
                borderRadius: '5px',
                messageColor: '#fff',

            });
        }else{
            Notiflix.Block.pulse('#loginFormView', 'Please Wait', {
                backgroundColor: '#fff',
                color: '#000',
                fontSize: '16px',
                borderRadius: '5px',
                messageColor: '#000',
            });
        }
        Notiflix.Block.pulse('#loginFormView');

        const formData = new FormData(loginForm);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(loginForm.getAttribute('action'), {
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
                Notiflix.Block.remove('#loginFormView');
                document.getElementById('submit').disabled = true;
                Notiflix.Report.success(
                    'Login berhasil!',
                    'Anda akan diarahkan ke halaman dashboard dalam 2 detik.',
                    'Okay',
                );
                window.setTimeout(function() {
                    window.location.href = data.redirect_url;
                }, 2000);
            } else {
                document.getElementById('password').value = '';
                window.setTimeout(function() {
                    Notiflix.Notify.failure(data.message || 'Login gagal.');
                }, 2000);
                Notiflix.Block.remove('#loginFormView', 3000);
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('Terjadi kesalahan saat memproses login.');
            Notiflix.Block.remove('#loginFormView', 3000);
        });
    });
});
