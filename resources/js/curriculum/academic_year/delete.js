document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-academic-year-btn')) {
        const button = event.target;
        const academicYearData = {
            id: button.getAttribute('data-id'),
            academic_year: button.getAttribute('data-academic_year'),
        };

        Alpine.store('deleteModal').show(academicYearData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteModal', {
        open: false,
        academicYear: {},
        show(academicYearData) {
            this.academicYear = academicYearData;
            this.open = true;

            document.getElementsByClassName('academicYear-value')[0].value = '';
        },
        close() {
            this.open = false;
            this.academicYear = {}; // Reset data when modal is closed
        }
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {

            const deleteAcademicYearForm = this.$refs.deleteAcademicYearForm;
            if (!deleteAcademicYearForm) {
                console.error('Form Not Found');
                return;
            }
            // Get values based on input names
            const academicYearId = deleteAcademicYearForm.elements['academicYearId'].value;
            const academicYearInput = deleteAcademicYearForm.elements['academicYearInput'].value;
            const originalAcademicYear = deleteAcademicYearForm.elements['originalAcademicYear'].value;

            // Validate empty inputs
            if (!academicYearId || !academicYearInput) {
                let missingFields = [];

                if (!academicYearId) missingFields.push('Academic Year ID');
                if (!academicYearInput) missingFields.push('Academic Year');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }

            if(academicYearInput !== originalAcademicYear) {
                Notiflix.Notify.failure(`The academic year must match.`);
                return;
            }

            const formData = new FormData(deleteAcademicYearForm);
            formData.append('_method', 'DELETE');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/delete/${academicYearId}`;
            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    deleteAcademicYearForm.reset();
                    Notiflix.Notify.success('Academic Year has been successfully deleted.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the academic year.');
                console.error('Error:', error);
            })
            .finally(() => {
            });
        }
    };
}