document.addEventListener('alpine:init', () => {
    Alpine.data('classListComponent', () => ({
        openJoinModalState: false,
        joinData: {},
        openJoinModal(data) {
            this.joinData = data;
            this.openJoinModalState = true;
        },
        closeJoinModal() {
            this.openJoinModalState = false;
            this.joinData = {};
        },
        submitForm() {
            const form = this.$refs.joinForm;
            if (form) {
                submitJoinForm(form); // Panggil fungsi eksternal dari update.js
            }
        },
    }));
});