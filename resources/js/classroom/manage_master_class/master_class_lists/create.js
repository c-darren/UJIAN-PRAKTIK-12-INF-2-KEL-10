function openCreateModalButton() {
    document.getElementById('openCreateModalButton').click();
}
document.addEventListener('DOMContentLoaded', function () {
    const createForm = document.getElementById('createForm');

    if (createForm) {
        let isSubmitting = false;

        createForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            // Validasi input di JS
            const className = document.getElementById('class_name').value.trim();
            const subjectId = document.getElementById('subject_id').value;
            const enrollmentStatus = document.getElementById('enrollment_status').value;

            let missingFields = [];
            if (!className) missingFields.push('Class Name');
            if (!subjectId) missingFields.push('Subject');
            if (!enrollmentStatus) missingFields.push('Enrollment Status');

            if (missingFields.length > 0) {
                Notiflix.Notify.failure(`This field is required: ${missingFields.join(', ')}`);
                isSubmitting = false;
                return;
            }

            const formData = new FormData(createForm);

            axios.post(`/classroom/masterClass/manage/${window.masterClassId}/class_lists/store`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.data.success) {
                    Notiflix.Notify.success(response.data.message, {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    createForm.reset();
                    document.getElementById('closeCreateModalButton').click();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    Notiflix.Report.failure('Failed to Create', response.data.message, 'OK');
                }
            })
            .catch(error => {
                const errorMessage = error.response && error.response.data && error.response.data.message
                    ? error.response.data.message
                    : 'An unexpected error occurred.';
                Notiflix.Report.failure('Failed to Create', errorMessage, 'OK');
            })
            .finally(() => {
                setTimeout(() => {
                    isSubmitting = false;
                }, 2000);
            });
        });
    }
});