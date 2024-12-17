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

            const topic_name = editForm.elements['topic_name'].value;
            const actionUrl = editForm.elements['actionUrl'].value;
            
            if (!topic_name) {
                let missingFields = [];
        
                if (!topic_name) missingFields.push('Topic Name');
        
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
                    Notiflix.Notify.success('The topic has been successfully updated.', {
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