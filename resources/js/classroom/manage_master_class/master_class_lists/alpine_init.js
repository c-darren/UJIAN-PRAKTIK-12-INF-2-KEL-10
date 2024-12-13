document.addEventListener('alpine:init', () => {
    Alpine.data('classListComponent', () => ({
        openCreateModal: false,
        openEditModalState: false,
        editData: {},
        openEditModal(data) {
            this.editData = data;
            this.openEditModalState = true;
        },
        closeEditModal() {
            this.openEditModalState = false;
            this.editData = {};
        },
        submitForm() {
            const form = this.$refs.editForm;
            if (form) {
                submitEditForm(form); // Panggil fungsi eksternal dari update.js
            }
        },
        deleteClassList(id) {
            // Implementasi fungsi delete jika diperlukan
        }
    }));
});