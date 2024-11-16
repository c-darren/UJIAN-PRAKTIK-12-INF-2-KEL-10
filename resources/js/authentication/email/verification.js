function submitResendEmailForm() {
    const resendForm = document.getElementById('resend-form');
    const resendButton = document.getElementById('resend-button');
    let resendCooldown = false;
    let data = null; // Definisikan data untuk digunakan di semua blok

    if (resendCooldown) return;

    if (!resendForm) {
        Notiflix.Notify.failure('Resend form not found.');
        return;
    }

    // Ganti teks tombol dengan loading icon bawaan Tailwind
    resendButton.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Resending...
    `;

    const formData = new FormData(resendForm);
    const csrfToken = resendForm.querySelector('input[name="_token"]').value;
    const actionUrl = resendForm.action;

    resendCooldown = true;
    resendButton.disabled = true;

    fetch(actionUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
    })
        .then(async response => {
            if (response.status === 429) {
                data = await response.json();
                return Promise.reject(data); // Lempar error untuk status 429
            }

            if (!response.ok) {
                throw new Error('An unexpected error occurred.');
            }

            return response.json(); // Ambil JSON untuk status 200
        })
        .then(responseData => {
            data = responseData;
            if (data.success) {
                Notiflix.Notify.success('Verification email resent successfully.', {
                    timeout: 2000,
                    clickToClose: true,
                });
            }
        })
        .catch(errorData => {
            if (errorData?.secondsLeft) {
                Notiflix.Notify.failure(errorData.message || 'Failed to resend verification email.');
                countdown(Math.round(errorData.secondsLeft)); // Jalankan countdown dari error
            } else {
                Notiflix.Notify.failure('An error occurred while resending the verification email.');
                countdown(60); // Jalankan countdown default
            }
        })
        .finally(() => {
            // Jalankan countdown jika tidak ada error
            const secondsLeft = typeof data?.secondsLeft === 'number' ? data.secondsLeft : 60;
            countdown(Math.round(secondsLeft));
            resendCooldown = false;
        });
}

function countdown(seconds) {
    const resendButton = document.getElementById('resend-button');
    let countdownValue = seconds;

    resendButton.innerHTML = `Resend in ${countdownValue}s`;

    const countdownInterval = setInterval(() => {
        countdownValue -= 1;
        if (countdownValue > 0) {
            resendButton.innerHTML = `Resend in ${countdownValue}s`;
        } else {
            clearInterval(countdownInterval);
            resendButton.disabled = false;
            resendButton.innerHTML = 'Resend Verification Email';
        }
    }, 1000); // Interval 1 detik
}

