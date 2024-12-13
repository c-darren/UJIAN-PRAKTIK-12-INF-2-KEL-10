document.addEventListener('DOMContentLoaded', function () {

    // Fungsi untuk menampilkan modal konfirmasi
    function showConfirmModal(title, body, actionUrl) {
        const modal = document.getElementById('confirmModal');
        const titleElem = document.getElementById('confirmModalTitle');
        const bodyElem = document.getElementById('confirmModalBody');
        const yesBtn = document.getElementById('confirmYesBtn');

        titleElem.textContent = title;
        bodyElem.textContent = body;
        yesBtn.setAttribute('data-actionUrl', actionUrl);

        // Tampilkan modal (hapus class hidden)
        modal.classList.remove('hidden');
    }

    function hideConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
    }

    window.hideConfirmModal = hideConfirmModal; // agar dapat dipanggil dari onclick di tombol Cancel

    // Modifikasi handleDeleteStudent untuk dipanggil setelah konfirmasi
    async function handleDeleteStudent(actionUrl) {
        const searchButton = document.getElementById('search-button');
        if (!actionUrl) {
            console.error('Action URL not found.');
            Notiflix.Notify.failure('Action URL not found.');
            return;
        }

        try {
            const response = await axios.delete(actionUrl);
            const data = response.data;

            if (data.success) {
                Notiflix.Notify.success(data.message);
                searchButton.click();
            } else {
                Notiflix.Notify.failure(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            if (error.response && error.response.data && error.response.data.message) {
                Notiflix.Notify.failure(error.response.data.message);
            } else {
                Notiflix.Notify.failure('An error occurred while removing the student.');
            }
        }
    }

    // Fungsi untuk handle Add Student (tidak memerlukan konfirmasi, tapi jika ingin bisa serupa)
    async function handleAddStudent(button) {
        const actionUrl = button.getAttribute('data-actionUrl');
        const studentId = button.getAttribute('data-studentId');
        const searchButton = document.getElementById('search-button');

        if (!actionUrl || !studentId) {
            console.error('Action URL or Student ID not found.');
            document.getElementById('search-button').click();
            Notiflix.Notify.failure('Action URL or Student ID not found.');
            return;
        }

        try {
            const response = await axios.post(actionUrl, { student_id: studentId });
            const data = response.data;

            if (data.success) {
                Notiflix.Notify.success(data.message);
                searchButton.click();
            } else {
                Notiflix.Notify.failure(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            if (error.response && error.response.data && error.response.data.message) {
                Notiflix.Notify.failure(error.response.data.message);
            } else {
                Notiflix.Notify.failure('An error occurred while adding the student.');
            }
        }
    }

    // Event Delegation untuk tombol Add dan Delete
    document.body.addEventListener('click', function (event) {
        const addButton = event.target.closest('.add-button');
        const deleteButton = event.target.closest('.delete-button');

        if (addButton) {
            event.preventDefault();
            handleAddStudent(addButton);
        }

        if (deleteButton) {
            event.preventDefault();
            const actionUrl = deleteButton.getAttribute('data-actionUrl');
            const studentName = deleteButton.getAttribute('data-name');
            showConfirmModal('Confirm Deletion', `Are you sure you want to remove ${studentName} from this class?`, actionUrl);
        }
    });

    // Jika tombol confirm di modal ditekan
    document.getElementById('confirmYesBtn').addEventListener('click', function() {
        const actionUrl = this.getAttribute('data-actionUrl');
        hideConfirmModal();
        handleDeleteStudent(actionUrl);
    });
});