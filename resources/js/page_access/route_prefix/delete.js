
function showDeleteModal(button) {
    const deleteUrl = button.getAttribute('data-delete_url');
    const deletePrefixUrl = button.getAttribute('data-delete_prefix_url');
    const modal = document.getElementById('deleteModal');
    const displayRoutePrefixUrl = document.getElementById('display_prefix_url_delete');
    displayRoutePrefixUrl.textContent = deletePrefixUrl;
    
    modal.removeAttribute('data-delete_url');
    modal.setAttribute('data-delete_url', deleteUrl);
    modal.removeAttribute('data-delete_prefix_url');
    modal.setAttribute('data-delete_prefix_url', deletePrefixUrl);
    displayRoutePrefixUrl.classList.add('font-bold');

};
function confirmDelete(button) {
    const modal = document.getElementById('deleteModal');
    const csrfToken = button.getAttribute('data-csrf');
    const deleteUrl = modal.getAttribute('data-delete_url');

    const deletePrefixUrl = modal.getAttribute('data-delete_prefix_url');
    var confirm_route_prefix_url = document.getElementById('confirm_route_prefix_url').value;

    if(button.disabled) {
        return;
    }
    button.disabled = true;
    if (!confirm_route_prefix_url) {
        Notiflix.Notify.failure(
            'Please enter the Route Prefix URL to confirm.',
        )
        window.setTimeout(function() {
            button.disabled = false;
            button.removeAttribute('disabled');
        }, 2500);
        return;
    } else if (confirm_route_prefix_url != deletePrefixUrl) {
        Notiflix.Notify.failure(
            'Route Prefix URL does not match.',
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
                'Successfully deleted route prefix',
                'Okay',
            )
        } else {
            Notiflix.Notify.failure(
                'Failed to delete route prefix',
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

