document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.delete-subject-btn')) {
        const button = event.target;
        const subjectData = {
            id: button.getAttribute('data-id'),
            subject_name: button.getAttribute('data-subject_name'),
        };

        Alpine.store('deleteModal').show(subjectData);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('deleteModal', {
        open: false,
        subject: {},
        show(subjectData) {
            this.subject = subjectData;
            this.open = true;

            document.getElementsByClassName('subject-value')[0].value = '';
        },
        close() {
            this.open = false;
            this.subject = {}; // Reset data when modal is closed
        }
    });
});

function deleteModalData() {
    return {
        isSubmitting: false,
        submitDeleteForm() {

            const deletesubjectForm = this.$refs.deletesubjectForm;
            if (!deletesubjectForm) {
                console.error('Form Not Found');
                return;
            }
            // Get values based on input names
            const subjectId = deletesubjectForm.elements['subjectId'].value;
            const subjectInput = deletesubjectForm.elements['subjectInput'].value;
            const originalsubject = deletesubjectForm.elements['originalsubject'].value;

            // Validate empty inputs
            if (!subjectId || !subjectInput) {
                let missingFields = [];

                if (!subjectId) missingFields.push('Subject ID');
                if (!subjectInput) missingFields.push('Subject Name');

                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }

            if(subjectInput !== originalsubject) {
                Notiflix.Notify.failure(`The subject must match.`);
                return;
            }

            const formData = new FormData(deletesubjectForm);
            formData.append('_method', 'DELETE');

            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/delete/${subjectId}`;
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
                    deletesubjectForm.reset();
                    Notiflix.Notify.success('Subject has been successfully deleted.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('deleteModal').close();
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure('An error occurred while deleting the subject.');
                console.error('Error:', error);
            })
            .finally(() => {
            });
        }
    };
}