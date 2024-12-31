document.addEventListener('DOMContentLoaded', function () {
    const joinForms = document.querySelectorAll('[x-ref="joinForm"]');
    
    joinForms.forEach(joinForm => {
        joinForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    });
});

function submitJoinForm(form) {
    form.querySelector('button[type="submit"]').disabled = true;
    Notiflix.Notify.info('Mengirim permintaan...', { timeout: 2000 });

    const data = {
        id: form.elements['id'].value,
    };

    const classListId = data.id;
    const baseUrl = `/master-classes/${window.masterClassId}/classroom/student_join/${classListId}`;

    axios.post(baseUrl, data)
        .then(response => {
            if (response.data.success) {
                Notiflix.Notify.success(response.data.message);
                document.getElementById('cancelButton').click();
                form.reset();
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                Notiflix.Notify.failure(response.data.message);
            }
        })
        .catch(error => {
            Notiflix.Notify.failure(
                error.response?.data?.message || 
                'Terjadi kesalahan saat bergabung ke kelas'
            );
        })
        .finally(() => {
            form.querySelector('button[type="submit"]').disabled = false;
        });
}
