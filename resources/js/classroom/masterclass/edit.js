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
            id: button.getAttribute('data-id'),
            col_01: button.getAttribute('data-col_01'),
            col_02: button.getAttribute('data-col_02'),
            col_03: button.getAttribute('data-col_03'),
            col_04: button.getAttribute('data-col_04'),
            col_05: button.getAttribute('data-col_05'),
            col_06: button.getAttribute('data-col_06'),
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

            const id = editForm.elements['id'].value;
            const master_class_name = editForm.elements['master_class_name'].value;
            const master_class_code = editForm.elements['master_class_code'].value;
            const academic_year_id = editForm.elements['academic_year_id'].value;
            const status = editForm.elements['status'].value;
            
            if (!id || !master_class_name) {
                let missingFields = [];
        
                if (!id) missingFields.push('ID');
                if (!master_class_name) missingFields.push('Master Class Name');
                if (!master_class_code) missingFields.push('Master Class Code');
                if (!academic_year_id) missingFields.push('Academic Year');
                if (!status) missingFields.push('Status');
        
                Notiflix.Notify.failure(`The following fields are required: ${missingFields.join(', ')}`);
                this.isSubmitting = false;
                return;
            }

            const formData = new FormData(editForm);
            formData.append('_method', 'PUT');
        
            const baseUrl = `${window.location.origin}${window.location.pathname.split('/').slice(0, -1).join('/')}`;
            const actionUrl = `${baseUrl}/edit/${id}`;
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
                    Notiflix.Notify.success('Master Class has been successfully updated.', {
                        timeout: 2000,
                        clickToClose: true,
                    });
                    Alpine.store('editModal').close();
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
                button.getElementById('search-button').click();
            });
        }
    };
}