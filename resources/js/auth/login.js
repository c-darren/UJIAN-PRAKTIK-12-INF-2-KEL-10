document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman

        // Ambil data form
        const formData = new FormData(loginForm);
        
        // Kirim form menggunakan AJAX
        fetch(loginForm.getAttribute('action'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Notiflix.Notify.success('Login berhasil!');
                window.location.href = data.redirect_url; // Redirect setelah login sukses
            } else {
                Notiflix.Notify.failure(data.message || 'Login gagal.');
            }
        })
        .catch(error => {
            Notiflix.Notify.failure('Terjadi kesalahan saat memproses login.');
        });
    });
});
