document.addEventListener('alpine:init', () => {
    // Definisikan Alpine Component untuk Assignment Table
    Alpine.data('assignmentTable', () => ({
        assignmentForm: {
            topic_id: '',
            assignment_name: '',
            description: '',
            start_date: '',
            end_date: '',
            accept_late_submissions: false,
            attachment: [],
        },

        /**
         * Menangani upload file lampiran.
         */
        handleAssignmentFileUpload(event) {
            this.assignmentForm.attachment = event.target.files;
        },

        /**
         * Mengirim formulir assignment.
         */
        submitAssignmentForm() {
            const searchButton = document.getElementById('search-button');
            // Validasi dengan Notiflix
            if (this.assignmentForm.topic_id === '') {
                Notiflix.Notify.failure('Topik tidak boleh kosong.');
                return;
            }

            if (this.assignmentForm.assignment_name.trim() === '') {
                Notiflix.Notify.failure('Nama tugas tidak boleh kosong.');
                return;
            }

            if (this.assignmentForm.start_date === '') {
                Notiflix.Notify.failure('Tanggal mulai tidak boleh kosong.');
                return;
            }

            if (this.assignmentForm.end_date === '') {
                Notiflix.Notify.failure('Tanggal selesai tidak boleh kosong.');
                return;
            }

            if (new Date(this.assignmentForm.end_date) < new Date(this.assignmentForm.start_date)) {
                Notiflix.Notify.failure('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                return;
            }
            let formData = new FormData();
            const createAssignmentForm = document.getElementById('createAssignmentForm');
            const actionUrl = createAssignmentForm.getAttribute('action');

            formData.append('topic_id', this.assignmentForm.topic_id);
            formData.append('assignment_name', this.assignmentForm.assignment_name);
            formData.append('description', this.assignmentForm.description);
            formData.append('start_date', this.assignmentForm.start_date);
            formData.append('end_date', this.assignmentForm.end_date);
            formData.append('accept_late_submissions', this.assignmentForm.accept_late_submissions);

            for (let i = 0; i < this.assignmentForm.attachment.length; i++) {
                formData.append('attachment[]', this.assignmentForm.attachment[i]);
            }

            // Disable tombol saat pengiriman
            const submitBtn = document.getElementById('submitAssignmentCreate');
            submitBtn.disabled = true;

            Notiflix.Notify.info('Menambahkan tugas..., Mohon tunggu sebentar.');
            axios.post(actionUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if(response.data.success){
                    Notiflix.Notify.success('Tugas telah ditambahkan.');
                    this.resetAssignmentForm();
                    Alpine.store('createAssignmentModal').close();
                    searchButton.click();
                } else {
                    Notiflix.Notify.failure('Tidak dapat menambahkan tugas.');
                }
            })
            .catch(error => {
                alert(error);
                console.error(error);
                Notiflix.Notify.failure(error || 'Terjadi kesalahan saat menambahkan tugas.');
            })
            .finally(() => {
                submitBtn.disabled = false;
            });
        },

        /**
         * Mengatur ulang formulir assignment.
         */
        resetAssignmentForm() {
            this.assignmentForm.topic_id = '';
            this.assignmentForm.assignment_name = '';
            this.assignmentForm.description = '';
            this.assignmentForm.start_date = '';
            this.assignmentForm.end_date = '';
            this.assignmentForm.accept_late_submissions = false;
            this.assignmentForm.attachment = [];
            document.getElementById('createAssignmentForm').reset();
        },
    }));
});