document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.read-more-role-btn')) {
        const button = event.target;
        const roleData = {
            id: button.getAttribute('data-id'),
            role_name: button.getAttribute('data-role_name'),
            desc: button.getAttribute('data-desc'),
        };

        Alpine.store('readmoreModal').show(roleData);
    }
});
document.addEventListener('alpine:init', () => {
    Alpine.store('readmoreModal', {
        open: false,
        role: {},
        show(roleData) {
            this.role = roleData;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});