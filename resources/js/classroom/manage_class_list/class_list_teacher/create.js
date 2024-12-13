
function openCreateModalButton() {
    document.getElementById('showCreateModal').click();
}
document.addEventListener('DOMContentLoaded', function () {
    const createForm = document.getElementById('createForm');

    if (createForm) {
        createForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
});
// document.addEventListener('click', function(event) {
//     if (event.target && event.target.matches('.create-btn')) {
//         Alpine.store('createModal').show();
//     }
// });

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
        resetCreateForm() {
            const createForm = this.$refs.createForm;
            if (createForm) {
                createForm.reset();
            }
        },
        submitCreateForm(event) {
            event.preventDefault();

            if (this.isSubmitting){
                setTimeout(() => {
                    Notiflix.Notify.info('Please wait...');
                }, 1800);
                return;
            }
            this.isSubmitting = true;
        
            const createForm = this.$refs.createForm;
            if (!createForm) {
                console.error('Form Not Found');
                this.isSubmitting = false;
                return;
            }

            const submitButton = createForm.querySelector('#submit_form');
            const availableTeacherDatalist = createForm.querySelector('#available_teacher');
            const teacherNameInput = createForm.querySelector('[name="teacher_id"]');
            const searchButton = document.getElementById('search-button');

            if(!teacherNameInput) {
                Notiflix.Notify.failure('Please select a teacher from the list', { timeout: 1500 });
                this.isSubmitting = false;
                return;
            }

            if (!teacherNameInput.value.trim()) {
                Notiflix.Notify.failure('Please select a teacher from the list', { timeout: 1500 });
                this.isSubmitting = false;
                return;
            }
    
            const inputValue = teacherNameInput.value.trim();
            const matches = inputValue.match(/^(\d+)\s*-\s*(.+)$/);
            
            if (!matches) {
                Notiflix.Notify.failure('Please select a valid teacher from the list', { timeout: 1500 });
                this.isSubmitting = false;
                return;
            }

            const teacherId = matches[1];

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-2 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.3542C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Adding...
                `;
            }

            const formData = new FormData(createForm);
            formData.set('teacher_id', teacherId);
            const actionUrl = createForm.action;
    
            axios.post(actionUrl, formData, {
                headers: {
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (response.data.success) {
                    setTimeout(() => {
                        searchButton.click();
                        Notiflix.Notify.success(response.data.message, {
                            timeout: 1500
                        });
                        const selectedOption = availableTeacherDatalist.querySelector(`option[value="${inputValue}"]`);
                        if (selectedOption) {
                            selectedOption.remove();
                        }
        
                        createForm.reset();
                        
                        if (Alpine.store('createModal')) {
                            Alpine.store('createModal').close();
                        }
                    }, 1800);
                } else {
                    setTimeout(() => {
                        Notiflix.Notify.failure(response.data.message || 'Failed to submit form.', { timeout: 1500 });
                    }, 1800);
                }
            })
            .catch(error => {
                const errorMessage = error.response?.data?.message || 'An error occurred.';
                setTimeout(() => {
                    Notiflix.Notify.failure(errorMessage, { timeout: 2000 });
                }, 1800);
            })
            .finally(() => {
                setTimeout(() => {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Add Teacher';
                    }
                    this.isSubmitting = false;
                }, 2000);
            });
        }
    };
}