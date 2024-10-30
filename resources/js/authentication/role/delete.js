
function showDeleteModal(button) {
    const deleteUrl = button.getAttribute('data-delete_url');
    const deleteRoleName = button.getAttribute('data-delete_role_name');
    const modal = document.getElementById('deleteModal');
    const displayRole = document.getElementById('display_delete_role_name');
    displayRole.textContent = deleteRoleName;
    
    submitButton = document.getElementById('submit_delete_button');
    submitButton.removeAttribute('data-delete_url');
    submitButton.setAttribute('data-delete_url', deleteUrl);
    submitButton.removeAttribute('data-delete_role_name');
    submitButton.setAttribute('data-delete_role_name', deleteRoleName);
    displayRole.classList.add('font-bold');

};
function confirmDelete(button) {
    const modal = document.getElementById('deleteModal');
    const csrfToken = button.getAttribute('data-csrf');
    const deleteUrl = button.getAttribute('data-delete_url');
    const closeDeleteModal = document.getElementById('close_delete_modal');

    const deleteRoleName = button.getAttribute('data-delete_role_name');
    var delete_role_name = document.getElementById('confirm_role').value;

    if(button.disabled) {
        return;
    }
    button.disabled = true;
    if (!delete_role_name) {
        Notiflix.Notify.failure(
            'Please enter the role to confirm.',
        )
        window.setTimeout(function() {
            button.disabled = false;
            button.removeAttribute('disabled');
        }, 2500);
        return;
    } else if (delete_role_name != deleteRoleName) {
        Notiflix.Notify.failure(
            'Role does not match.',
        )
        window.setTimeout(function() {
            button.disabled = false;
            button.removeAttribute('disabled');
        }, 2500);
        return;
    }

    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Notiflix.Notify.success(
                'Successfully deleted role',
                'Okay',
            )
            closeDeleteModal.click();
            window.setTimeout(function() {
                window.location.reload();
            }, 1000);
        } else {
            Notiflix.Notify.failure(
                'Failed to delete role',
                'Okay'
            )
        }
    })

    .catch(error => {
        console.error('Error:', error);
    })
    .finally(() => {
        window.setTimeout(function() {
            button.disabled = false;
            button.removeAttribute('disabled');
        }, 2500);
    });
}

