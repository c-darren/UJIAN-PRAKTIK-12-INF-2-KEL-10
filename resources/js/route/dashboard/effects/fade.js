document.getElementById('user-menu-button-2').addEventListener('click', function() {
    let dropdown = document.getElementById('dropdown-2');

    if (dropdown.classList.contains('hidden')) {
        // Jika dropdown disembunyikan, tampilkan dan mulai animasi fade-in
        dropdown.classList.remove('hidden');
        setTimeout(() => {
            dropdown.classList.remove('opacity-0');
            dropdown.classList.add('opacity-100');
        }, 0);
    } else {
        // Jika dropdown sudah ditampilkan, mulai animasi fade-out
        dropdown.classList.remove('opacity-100');
        dropdown.classList.add('opacity-0');

        // Pastikan hanya menambahkan 'hidden' setelah animasi selesai
        dropdown.addEventListener('transitionend', function() {
            // Jika dropdown sedang tidak dalam keadaan visible, sembunyikan
            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.add('hidden');
            }
        }, { once: true }); // Gunakan 'once' agar listener dijalankan satu kali saja
    }
});
