document.addEventListener('alpine:init', () => {
    Alpine.data('materialTable', () => ({
        isGrid: true,
    }));
});