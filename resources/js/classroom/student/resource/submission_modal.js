document.addEventListener('alpine:init', () => {
    Alpine.data('submissionManager', () => ({
        showAddSubmissionModal: false,
        showMarkCompleteModal: false,
        selectedFiles: [],
        deleteAttachmentsList: [],
        deleteAttachmentsNames: [],

        init() {
            // console.log('Submission manager initialized');
        },

        toggleAddSubmissionModal() {
            this.showAddSubmissionModal = !this.showAddSubmissionModal;
            console.log('Add submission modal:', this.showAddSubmissionModal);
        },

        toggleMarkCompleteModal() {
            this.showMarkCompleteModal = !this.showMarkCompleteModal;
            console.log('Mark complete modal:', this.showMarkCompleteModal);
        },

        handleFiles(event) {
            this.selectedFiles = event.target.files;
        },

        toggleDeleteAttachment(path, fileName) {
            const index = this.deleteAttachmentsList.indexOf(path);
            if (index === -1) {
                this.deleteAttachmentsList.push(path);
                this.deleteAttachmentsNames.push(fileName);
            } else {
                this.deleteAttachmentsList.splice(index, 1);
                this.deleteAttachmentsNames.splice(index, 1);
            }
        },

        async submitAssignment(event) {
            event.preventDefault();
            const formData = new FormData();
            
            // Add new files
            for (let file of this.selectedFiles) {
                formData.append('attachments[]', file);
            }

            // Add deleted attachments
            this.deleteAttachmentsList.forEach(path => {
                formData.append('delete_attachments[]', path);
            });

            try {
                const response = await axios.post(
                    `/master-classes/${masterClass_id}/${classList_id}/resources/submissions/${assignment_id}/store`,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                );

                if (response.data.success) {
                    Notiflix.Notify.success('Tugas berhasil dikirim');
                    this.showAddSubmissionModal = false;
                    location.reload();
                }
            } catch (error) {
                Notiflix.Notify.failure(error.response?.data?.message || 'Gagal mengirim tugas');
            }
        },

        async markComplete() {
            try {
                const response = await axios.post(
                    `/master-classes/${masterClass_id}/${classList_id}/resources/submissions/${assignment_id}/mark-as-complete`
                );

                if (response.data.success) {
                    Notiflix.Notify.success('Tugas berhasil ditandai selesai');
                    this.showMarkCompleteModal = false;
                    location.reload();
                }
            } catch (error) {
                Notiflix.Notify.failure(error.response?.data?.message || 'Gagal menandai tugas selesai');
            }
        },

        async cancelSubmission(submissionId) {
            try {
                const response = await axios.post(
                    `/master-classes/${masterClass_id}/${classList_id}/resources/submissions/${submissionId}/cancel`
                );
        
                if (response.data.success) {
                    Notiflix.Notify.success('Pengumpulan tugas dibatalkan');
                    location.reload();
                }
            } catch (error) {
                Notiflix.Notify.failure(error.response?.data?.message || 'Gagal membatalkan pengumpulan');
            }
        }
    }));
});