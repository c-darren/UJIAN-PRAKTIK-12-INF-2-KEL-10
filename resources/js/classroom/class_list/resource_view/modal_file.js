document.addEventListener('DOMContentLoaded', function() {
    const previewModal = document.getElementById('previewModal');
    const modalContent = document.getElementById('modalContent');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const openInNewTabBtn = document.getElementById('openInNewTabBtn');
    const modalTitle = document.getElementById('modalTitle');

    // Saat link diklik
    document.querySelectorAll('.file-preview-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const fileUrl = this.getAttribute('data-fileUrl');
            const downloadUrl = this.getAttribute('data-downloadUrl');
            const fileType = this.getAttribute('data-fileType');
            const fileName = this.getAttribute('data-title');
            const downloadBtn = document.getElementById('downloadBtn');

            // Atur judul modal
            modalTitle.textContent = fileName;

            // Atur tombol open in new tab
            openInNewTabBtn.setAttribute('onclick', `window.open('${fileUrl}', '_blank')`);
            downloadBtn.setAttribute('onclick', `window.open('${downloadUrl}', '_blank')`);

            // Bersihkan konten modal sebelum menambahkan yang baru
            modalContent.innerHTML = '';

            if (fileType === 'pdf') {
                // Tampilkan PDF dalam iframe
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl + '#toolbar=0';
                iframe.classList.add('w-full', 'h-[70vh]');
                iframe.setAttribute('frameborder', '0');
                modalContent.appendChild(iframe);
            } else if (fileType === 'png' || fileType === 'jpg' || fileType === 'jpeg' || fileType === 'gif' || fileType === 'webp') {
                // Tampilkan image
                const img = document.createElement('img');
                img.src = fileUrl;
                img.classList.add('max-w-full', 'max-h-[70vh]');
                modalContent.appendChild(img);
            } else {
                // Jika tipe file belum didukung
                const msg = document.createElement('p');
                msg.textContent = 'File type not supported.';
                modalContent.appendChild(msg);
            }

            // Tampilkan modal
            previewModal.classList.remove('hidden');
        });
    });

    // Tombol close
    closeModalBtn.addEventListener('click', function() {
        previewModal.classList.add('hidden');
        modalContent.innerHTML = '';
    });

    // Klik di luar modal untuk menutup
    previewModal.addEventListener('click', function(e) {
        if (e.target === previewModal) {
            previewModal.classList.add('hidden');
            modalContent.innerHTML = '';
        }
    });
});