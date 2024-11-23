function openModal() {
    document.getElementById('avatarModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('avatarModal').classList.add('hidden');
}

function previewAvatar(event) {
    var output = document.getElementById('newAvatarPreview');
    var noNewAvatarText = document.getElementById('noNewAvatar');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
    }
    output.classList.remove('hidden');
    noNewAvatarText.classList.add('hidden');
}