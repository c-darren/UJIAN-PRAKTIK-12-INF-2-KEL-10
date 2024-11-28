document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.read-more-subject-btn')) {
        const button = event.target;
        const subjectData = {
            id: button.getAttribute('data-id'),
            subject_name: button.getAttribute('data-subject_name'),
        };

        Alpine.store('readmoreModal').show(subjectData);
    }
});
document.addEventListener('alpine:init', () => {
    Alpine.store('readmoreModal', {
        open: false,
        subject: {},
        show(subjectData) {
            this.subject = subjectData;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});
