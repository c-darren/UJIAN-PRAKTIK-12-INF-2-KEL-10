document.addEventListener('alpine:init', () => {
    Alpine.data('materialTable', () => ({
        form: {
            topic_id: '',
            material_name: '',
            description: '',
            start_date: '',
            attachment: [],
        },
        isGrid: true,

        handleFileUpload(event) {
            this.form.attachment = event.target.files;
        },

        submitForm() {

            const searchButton = document.getElementById('search-button');
            // Validasi dengan Notiflix
            if (this.form.topic_id === '') {
                Notiflix.Notify.failure('Topik tidak boleh kosong.');
                return;
            }

            if (this.form.material_name.trim() === '') {
                Notiflix.Notify.failure('Nama materi tidak boleh kosong.');
                return;
            }

            if (this.form.start_date === '') {
                Notiflix.Notify.failure('Tanggal tidak boleh kosong.');
                return;
            }

            let formData = new FormData();
            const createForm = document.getElementById('createForm');
            const actionUrl = createForm.getAttribute('action');

            formData.append('topic_id', this.form.topic_id);
            formData.append('material_name', this.form.material_name);
            formData.append('description', this.form.description);
            formData.append('start_date', this.form.start_date);
            for (let i = 0; i < this.form.attachment.length; i++) {
                formData.append('attachment[]', this.form.attachment[i]);
            }

            // Disable tombol saat pengiriman
            const submitBtn = document.getElementById('submitCreate');
            submitBtn.disabled = true;
            Notiflix.Notify.info('Menambahkan tugas..., Mohon tunggu sebentar.');
            axios.post(actionUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if(response.data.success){
                    Notiflix.Notify.success('Materi telah ditambahkan.');
                    this.resetForm();
                    Alpine.store('createModal').close();
                    searchButton.click();
                } else {
                    Notiflix.Notify.failure('Tidak dapat menambahkan materi.');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure(error || 'Terjadi kesalahan saat menambahkan materi.');
            })
            .finally(() => {
                setTimeout(() => {
                    submitBtn.disabled = false;
                }, 2000);
            });
        },

        resetForm() {
            this.form.topic_id = '';
            this.form.material_name = '';
            this.form.description = '';
            this.form.start_date = '';
            this.form.attachment = [];
            document.getElementById('createForm').reset();
        },
        
    }));
});