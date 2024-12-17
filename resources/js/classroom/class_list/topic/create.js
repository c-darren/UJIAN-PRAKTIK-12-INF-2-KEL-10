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
    const createModal = document.getElementById('createModal');
    const createForm = document.getElementById('createTopicForm');
    const submitCreateBtn = document.getElementById('submitCreate');

    createForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const topicName = document.getElementById('create_topic_name').value;

        if(topicName === '') {
            Notiflix.Notify.failure('Topik tidak boleh kosong.');
            return;
        }
        
        const actionUrl = createForm.getAttribute('action');

        submitCreateBtn.disabled = true;

        axios.post(actionUrl, {
            topic_name: topicName
        })
        .then(response => {
            if(response.data.success){
                Notiflix.Notify.success(response.data.message);
                setTimeout(() => {
                    submitCreateBtn.disabled = false;
                }, 2000);
                Alpine.store('createModal').close();
                setTimeout(() => {
                    window.location.reload();
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