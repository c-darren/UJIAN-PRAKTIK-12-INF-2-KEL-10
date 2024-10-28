function showViewModal(roleData) {
    var modal_view = document.getElementById("modal_view");
    document.getElementById("role_id").textContent = roleData.id;
    document.getElementById("role_name").textContent = roleData.name;
    document.getElementById("role_description").textContent = roleData.description;
}

function ViewModalButtons(roleData) {
    const editButton = document.getElementById('modal_view_edit_button');
    editButton.removeAttribute('href');

    const deleteButton = document.getElementById('modal_view_delete_button');
    deleteButton.removeAttribute('data-delete_url');
    deleteButton.removeAttribute('data-delete_role_name');

    editButton.setAttribute('href', roleData.edit_url);
    deleteButton.setAttribute('data-delete_url', roleData.delete_url);
    deleteButton.setAttribute('data-delete_prefix_url', prefixData.name);

}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('roles_table').addEventListener('click', function (event) {
        const button = event.target.closest('button[data-access_role_ids]');
            let roleData = {
                id: button.getAttribute('data-role-id'),
                name: button.getAttribute('data-role-name'),
                description: button.getAttribute('data-role-description'),

                edit_url: button.getAttribute('data-edit_url'),
                delete_url: button.getAttribute('data-delete_url'),
            };

            ViewModalButtons(roleData);
            showViewModal(roleData);
    });
});
