document.addEventListener('DOMContentLoaded', function () {
    const editSubjectForm = document.getElementById('editSubjectForm');
    
    if (editSubjectForm) {
        editSubjectForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.edit-subject-btn')) {
        const button = event.target;
        const subjectData = {
            id: button.getAttribute('data-id'),
            subject_name: button.getAttribute('data-subject_name')
        };

        Alpine.store('editModal').show(subjectData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('editModal', {
        open: false,
        subject: {},
        originalsubject: '',
        show(subjectData) {
            this.subject = subjectData;
            this.originalsubject = subjectData.subject_name
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
        
            const editSubjectForm = this.$refs.editsubjectForm;
            
            if (!editSubjectForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const subjectId = editSubjectForm.elements['id'].value;
            const subject = editSubjectForm.elements['subject_name'].value;
            const originalsubject = editSubjectForm.elements['originalsubject'].value;
            
            if (!subjectId || !subject) {
                let missingFields = [];
        
                if (!subjectId) missingFields.push('ID');
                if (!subject) missingFields.push('Subject');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            if(subject == originalsubject){
                Notiflix.Notify.info('No changes made to the subject. The subject must be different.');
                // Notiflix.Notify.failure(`The subject must be different.`);
                this.isSubmitting = false;
                return;
            }
            const formData = new FormData(editSubjectForm);
            formData.append('_method', 'PUT');
        
            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/edit/${subjectId}`;
            
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
                    Notiflix.Notify.success('Subject has been successfully updated.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('editModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while updating the subjects.');
                console.error('Error:', error);
            })
            .finally(() => {
                window.setTimeout(() => {
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}