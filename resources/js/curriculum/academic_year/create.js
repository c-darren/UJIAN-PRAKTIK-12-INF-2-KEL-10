function clickCreateAcademicYearModalButton() {
    document.getElementById('showCreateModal').click();
}
document.addEventListener('DOMContentLoaded', function () {
    const createAcademicYearForm = document.getElementById('createAcademicYearForm');

    if (createAcademicYearForm) {
        createAcademicYearForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});
document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.create-academic-year-btn')) {
        Alpine.store('createModal').show();
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('createModal', {
        open: false,
        show() {
            this.open = true;
        },
        close() {
            this.open = false;
        }
    });
});

function createModalData() {
    return {
        isSubmitting: false,
        // Function to reset the form
        resetCreateForm() {
            const createAcademicYearForm = this.$refs.createAcademicYearForm;
            if (createAcademicYearForm) {
                createAcademicYearForm.reset();
            }
        },
        submitCreateForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const createAcademicYearForm = this.$refs.createAcademicYearForm;
            if (!createAcademicYearForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }
        
            // Get values from form elements via x-ref
            const academicYear = createAcademicYearForm.querySelector('[name="academic_year"]').value;
            const status = createAcademicYearForm.querySelector('[name="status"]').value;
        
            // Validate empty inputs
            if (!academicYear || !status) {
                let missingFields = [];
        
                if (!academicYear) missingFields.push('Academic Year');
                if (!status) missingFields.push('Status');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }
        
            // Validate format for academicYear
            const academicYearPattern = /^[0-9]{4}-[0-9]{4}$/; // Regex for format YYYY-YYYY
            if (!academicYearPattern.test(academicYear)) {
                Notiflix.Notify.failure('The Academic Year must be in the format "YYYY-YYYY".');
                this.isSubmitting = false;
                return;
            }
        
            const formData = new FormData(createAcademicYearForm);
            formData.append('_method', 'POST');
        
            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/store`;
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
                    Notiflix.Notify.success('Academic Year has been successfully created.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('createModal').close();
                    this.resetCreateForm();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure(error.message);
                console.error('Error:', error);
            })
            .finally(() => {
                this.isSubmitting = false;
            });
        }
        
    };
}