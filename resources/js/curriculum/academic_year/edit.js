document.addEventListener('DOMContentLoaded', function () {
    const editAcademicYearForm = document.getElementById('editAcademicYearForm');
    
    if (editAcademicYearForm) {
        editAcademicYearForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.edit-academic-year-btn')) {
        const button = event.target;
        const academicYearData = {
            id: button.getAttribute('data-id'),
            academic_year: button.getAttribute('data-academic_year'),
            status: button.getAttribute('data-status'),
        };

        Alpine.store('editModal').show(academicYearData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('editModal', {
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

function editModalData() {
    return {
        isSubmitting: false,
        submitEditForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const editAcademicYearForm = this.$refs.editAcademicYearForm;
            if (!editAcademicYearForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }
        
            const academicYearId = editAcademicYearForm.elements['id'].value;
            const academicYear = editAcademicYearForm.elements['academic_year'].value;
            const status = editAcademicYearForm.elements['status'].value;
        
            // Validate empty inputs
            if (!academicYearId || !academicYear || !status) {
                let missingFields = [];
        
                if (!academicYearId) missingFields.push('ID');
                if (!academicYear) missingFields.push('Academic Year');
                if (!status) missingFields.push('Status');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }
        
            // Validate format for academicYear
            const academicYearPattern = /^[0-9]{4}-[0-9]{4}$/;
            if (!academicYearPattern.test(academicYear)) {
                Notiflix.Notify.failure('The Academic Year must be in the format "YYYY-YYYY".');
                this.isSubmitting = false;
                return;
            }
        
            const formData = new FormData(editAcademicYearForm);
            formData.append('_method', 'PUT');
        
            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/edit/${academicYearId}`;
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
                    Notiflix.Notify.success('Academic Year has been successfully updated.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('editModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while updating the academic year.');
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
        
    };
}