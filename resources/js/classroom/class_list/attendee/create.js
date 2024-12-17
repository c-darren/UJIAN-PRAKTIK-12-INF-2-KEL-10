document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.create-modal-btn')) {
        Alpine.store('createModal').show();
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('createModal', {
        open: false,
        show() {
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const createForm = document.getElementById('createForm');
    const submitCreateBtn = document.getElementById('submitCreate');

    createForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const topic_id = document.getElementById('topic_id').value;
        const attendee_date = document.getElementById('attendee_date').value;
        const description = document.getElementById('description').value;

        if(topic_id === '') {
            Notiflix.Notify.failure('Topik tidak boleh kosong.');
            return;
        }

        if(attendee_date === '') {
            Notiflix.Notify.failure('Tanggal tidak boleh kosong.');
            return;
        }
        
        const actionUrl = createForm.getAttribute('action');

        submitCreateBtn.disabled = true;

        axios.post(actionUrl, {
            topic_id,
            attendance_date: attendee_date,
            description
        })
        .then(response => {
            if(response.data.success){
                Notiflix.Notify.success(response.data.message);
                setTimeout(() => {
                    submitCreateBtn.disabled = false;
                }, 2000);
                Alpine.store('createModal').close();
                setTimeout(() => {
                    window.location.replace(response.data.redirectUrl);
                }, 1000);
            }else{
                Notiflix.Notify.failure(response.data.message);
            }
        })
        .catch(error => {
            Notiflix.Notify.failure(error.response.data.error || 'Gagal menambahkan topik.');
            setTimeout(() => {
                submitCreateBtn.disabled = false;
            }, 2000);
        });
    });
});