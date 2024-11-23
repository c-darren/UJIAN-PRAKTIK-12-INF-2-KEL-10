document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.read-more-btn')) {
        const button = event.target;
        const userData = {
            id: button.getAttribute('data-id'),
            name: button.getAttribute('data-name'),
            username: button.getAttribute('data-username'),
            email: button.getAttribute('data-email'),
            email_verified_at: button.getAttribute('data-email-verified-at'),
            role: button.getAttribute('data-role'),
            created_at: button.getAttribute('data-created-at'),
            updated_at: button.getAttribute('data-updated-at'),
            avatar: button.getAttribute('data-avatar'),
        };

        // Panggil fungsi untuk menampilkan modal dengan data user
        Alpine.store('userModal').show(userData);
    }
});
document.addEventListener('alpine:init', () => {
    Alpine.store('userModal', {
        open: false,
        user: {},
        show(userData) {
            this.user = userData;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});