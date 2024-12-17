document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('editForm');
    
    if (editForm) {
        editForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.matches('.edit-data-btn')) {
        const button = event.target;
        const data = {
            col_01: button.getAttribute('data-col_01'),
            col_02: button.getAttribute('data-col_02'),
            col_03: button.getAttribute('data-col_03'),
            actionUrl: button.getAttribute('data-actionUrl'),
        };

        Alpine.store('editModal').show(data);
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.store('editModal', {
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

function editModalData() {
    return {
        isSubmitting: false,
        submitEditForm() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
        
            const editForm = this.$refs.editForm;
            
            if (!editForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const topic_id = editForm.elements['topic_id'].value;
            const attendance_date = editForm.elements['attendance_date'].value;
            const actionUrl = editForm.elements['actionUrl'].value;
            
            if (!topic_id || !attendance_date) {
                let missingFields = [];
        
                if (!topic_id) missingFields.push('Topic Name');
                if (!attendance_date) missingFields.push('Attendee Date');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(editForm);
            formData.append('_method', 'PUT');
        
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
                    Notiflix.Notify.success('The data has been successfully updated.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('editModal').close();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notiflix.Report.failure('Failed', data.message, 'OK');
                }
            })
            .catch(error => {
                Notiflix.Notify.failure(error.message);
            })
            .finally(() => {
                window.setTimeout(() => {
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}