document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.read-more-academic-year-btn')) {
        const button = event.target;
        const academicYearData = {
            id: button.getAttribute('data-id'),
            academic_year: button.getAttribute('data-academic_year'),
            status: button.getAttribute('data-status'),
            created_at: button.getAttribute('data-created_at'),
            updated_at: button.getAttribute('data-updated_at'),
        };

        Alpine.store('readmoreModal').show(academicYearData);
    }
});
document.addEventListener('alpine:init', () => {
    Alpine.store('readmoreModal', {
        open: false,
        academicYear: {},
        show(academicYearData) {
            this.academicYear = academicYearData;
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});
