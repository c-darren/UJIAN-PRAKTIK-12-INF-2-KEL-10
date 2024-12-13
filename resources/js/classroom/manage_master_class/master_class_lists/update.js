document.addEventListener('DOMContentLoaded', function () {
    const editForms = document.querySelectorAll('[x-ref="editForm"]');
    
    editForms.forEach(editForm => {
        editForm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    });
});

function submitEditForm(form) {
    const className = form.elements['class_name'].value.trim();
    const subjectId = form.elements['subject_id'].value;
    const enrollmentStatus = form.elements['enrollment_status'].value;

    let missingFields = [];
    if (!className) missingFields.push('Class Name');
    if (!subjectId) missingFields.push('Subject');
    if (!enrollmentStatus) missingFields.push('Enrollment Status');

    if (missingFields.length > 0) {
        Notiflix.Notify.failure(`This field is required: ${missingFields.join(', ')}`);
        return;
    }

    form.querySelector('button[type="submit"]').disabled = true;
    Notiflix.Notify.info('Submitting form...', { timeout: 2000 });

    const data = {
        id: form.elements['id'].value,
        class_name: className,
        subject_id: subjectId,
        enrollment_status: enrollmentStatus,
    };

    const classListId = data.id;
    const baseUrl = `/classroom/masterClass/manage/${window.masterClassId}/class_lists/update/${classListId}`;

    axios.put(baseUrl, data, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        },
    })
    .then(response => {
        if (response.data.success) {
            Notiflix.Notify.success(response.data.message, {
                timeout: 2000,
                clickToClose: true,
            });
            document.getElementById('cancelButton').click();
            form.reset();
            const alpineComponent = form.closest('[x-data]');
            if (alpineComponent && alpineComponent.__x) {
                alpineComponent.__x.$data.closeEditModal();
            }

            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            console.log(response);
            Notiflix.Notify.failure(response.data.message);
        }
    })
    .catch(error => {
        setTimeout(() => {
            if (error.response && error.response.data && error.response.data.message) {
                Notiflix.Notify.failure(error.response.data.message);
            } else {
                Notiflix.Notify.failure('An error occurred while updating the class list.');
            }
        }, 2000);
    })
    .finally(() => {
        setTimeout(() => {
            form.querySelector('button[type="submit"]').disabled = false;
        }, 2000);
    });
}
