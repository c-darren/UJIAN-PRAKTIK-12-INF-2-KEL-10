document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('addForm');
    const studentNameInput = document.getElementById('student_name');
    const submitButton = document.getElementById('submit_form');

    addForm.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    addForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!studentNameInput.value.trim()) {
            Notiflix.Notify.failure('Please select a student from the list');
            return;
        }

        // Ekstrak ID dari input yang berformat "ID - Name"
        const inputValue = studentNameInput.value.trim();
        const matches = inputValue.match(/^(\d+)\s*-\s*(.+)$/);
        
        if (!matches) {
            Notiflix.Notify.failure('Please select a valid student from the list');
            return;
        }

        const studentId = matches[1]; // Ambil ID dari bagian pertama

        submitButton.disabled = true;
        submitButton.innerHTML = 'Adding...';
        
        const formData = new FormData(addForm);
        formData.set('student_id', studentId);

        const masterClassId = document.getElementById('master_class_id').value;
        const actionUrl = `/classroom/masterClass/manage/${masterClassId}/students/store`;

        axios.post(actionUrl, formData, {
            method: 'POST',
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            },
            body: formData,
        })
        .then(response => {
            const data = response.data;

            if (data.success) {
                Notiflix.Notify.success('Student added successfully', {
                    timeout: 2000,
                    clickToClose: true,
                });

                // Reset form
                addForm.reset();

                // Refresh halaman atau redirect jika diperlukan
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                Notiflix.Notify.failure(data.message || 'Failed to add student');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Tangani error dari server
            if (error.response) {
                const errorMessage = error.response.data.message || 'An error occurred while adding student';
                Notiflix.Notify.failure(errorMessage);
            } else {
                Notiflix.Notify.failure('Network error. Please try again.');
            }
        })
        .finally(() => {
            // Aktifkan kembali tombol submit
            submitButton.disabled = false;
            submitButton.innerHTML = 'Add Student';
        });
    });

    // Tambahkan event listener untuk datalist untuk validasi input
    studentNameInput.addEventListener('input', function() {
        const value = this.value.trim();
        const regex = /^\d+\s*-\s*.+$/;
        
        if (value && !regex.test(value)) {
            // Cari option terdekat yang cocok
            const matchingOption = Array.from(document.querySelectorAll('#non_enrolled_student_name option'))
                .find(option => option.value.toLowerCase().includes(value.toLowerCase()));
            
            if (matchingOption) {
                this.value = matchingOption.value;
            } else {
                this.value = '';
            }
        }
    });
});
