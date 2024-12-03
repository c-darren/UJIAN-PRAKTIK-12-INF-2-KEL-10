document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.read-more-data-btn')) {
        const button = event.target;
        const data = {
            id: button.getAttribute('data-id'),
            col_01: button.getAttribute('data-col_01'),
            col_02: button.getAttribute('data-col_02'),
            col_03: button.getAttribute('data-col_03'),
            col_04: button.getAttribute('data-col_04'),
            col_05: button.getAttribute('data-col_05'),
            col_06: button.getAttribute('data-col_06'),
        };

        Alpine.store('readmoreModal').show(data);
    }
});
document.addEventListener('alpine:init', () => {
    Alpine.store('readmoreModal', {
        open: false,
        data: {}, 
        show(tableData) { 
            this.data = tableData; 
            this.open = true; 
        },
        close() {
            this.open = false;
        }
    });
});
