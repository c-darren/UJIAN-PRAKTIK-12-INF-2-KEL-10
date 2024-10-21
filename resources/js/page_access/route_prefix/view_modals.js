function showViewModal(prefixData) {
    var modal_view = document.getElementById("modal_view");

    document.getElementById("created_date").textContent = prefixData.create_time;
    document.getElementById("edit_time").textContent = prefixData.edit_time;
    document.getElementById("prefix_name").textContent = prefixData.name;
    document.getElementById("prefix_id").textContent = prefixData.id;
    document.getElementById("created_by").textContent = prefixData.creator_name;
    document.getElementById("last_edited_by").textContent = prefixData.editor_name || 'N/A';
    document.getElementById("full_prefix_url").textContent = prefixData.full_url;
    document.getElementById("description").textContent = prefixData.description || '-';
    document.getElementById("group_type").textContent = prefixData.group_type || '-';
    document.getElementById("ip_address_type").textContent = prefixData.ip_address_type || '-';
    document.getElementById("ip_address").textContent = prefixData.ip_address || '-';
    document.getElementById("prefix_status").textContent = prefixData.prefix_status || '-';
    
    const classGroupType = document.getElementById("group_type").classList;
    if(classGroupType.contains('text-red-500')){
        document.getElementById("group_type").classList.remove('text-red-500');
    }
    if(classGroupType.contains('text-green-500')){
        document.getElementById("group_type").classList.remove('text-green-500');
    }
    if(prefixData.group_type == 'Blacklist'){
        document.getElementById("group_type").classList.add('text-red-500');
    }else{
        document.getElementById("group_type").classList.add('text-green-500');
    }

    const classIpAddressType = document.getElementById("ip_address_type").classList;
    if(classIpAddressType.contains('text-red-500')){
        document.getElementById("ip_address_type").classList.remove('text-red-500');
    }
    if(classIpAddressType.contains('text-green-500')){
        document.getElementById("ip_address_type").classList.remove('text-green-500');
    }
    if(prefixData.ip_address_type == 'Blacklist'){
        document.getElementById("ip_address_type").classList.add('text-red-500');
    }else{
        document.getElementById("ip_address_type").classList.add('text-green-500');
    }

    const classPrefixStatus = document.getElementById("prefix_status").classList;
    if(classPrefixStatus.contains('bg-red-600')){
        document.getElementById("prefix_status").classList.remove('bg-red-600');
    }
    if(classPrefixStatus.contains('bg-green-600')){
        document.getElementById("prefix_status").classList.remove('bg-green-600');
    }
    if(prefixData.prefix_status == 'Disabled'){
        document.getElementById("prefix_status").classList.add('bg-red-600');
    }else{
        document.getElementById("prefix_status").classList.add('bg-green-600');
    }

    // Filter roles
    const accessRoleIds = prefixData.access_role_ids.split(',').map(id => id.trim());
    const matchingRoles = window.roles.filter(role => accessRoleIds.includes(role.id.toString()));

    const rolesList = document.getElementById("roles_list");
    rolesList.innerHTML = '';
    matchingRoles.forEach(role => {
        let li = document.createElement("li");
        li.textContent = role.role;
        rolesList.appendChild(li);
    });

    // Filter groups
    const groupIds = prefixData.group_ids.split(',').map(id => id.trim());
    const matchingGroups = window.groups.filter(group => groupIds.includes(group.id.toString()));

    const groupsList = document.getElementById("groups_list");
    groupsList.innerHTML = '';
    matchingGroups.forEach(group => {
        let li = document.createElement("li");
        li.textContent = group.group_name;
        groupsList.appendChild(li);
    });
}

function ViewModalButtons(prefixData) {
    const editButton = document.getElementById('modal_view_edit_button');
    editButton.removeAttribute('href');

    const deleteButton = document.getElementById('modal_view_delete_button');
    deleteButton.removeAttribute('data-delete_url');
    deleteButton.removeAttribute('data-delete_prefix_url');

    editButton.setAttribute('href', prefixData.edit_url);
    deleteButton.setAttribute('data-delete_url', prefixData.delete_url);
    deleteButton.setAttribute('data-delete_prefix_url', prefixData.full_url);
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('button[data-access_role_ids]').forEach(button => {
        button.addEventListener('click', function () {
            let prefixData = {
                id: this.getAttribute('data-prefix-id'),
                name: this.getAttribute('data-prefix-name'),
                creator_name: this.getAttribute('data-creator-name'),
                editor_name: this.getAttribute('data-editor-name'),
                full_url: this.getAttribute('data-full_prefix_url'),
                description: this.getAttribute('data-description'),

                access_role_ids: this.getAttribute('data-access_role_ids'),
                group_type: this.getAttribute('data-group_type'),
                group_ids: this.getAttribute('data-group_ids'),

                ip_address_type: this.getAttribute('data-ip_address_type'),
                ip_address: this.getAttribute('data-ip_address'),
                prefix_status: this.getAttribute('data-prefix_status'),
                start_date: this.getAttribute('data-start_date'),
                valid_until: this.getAttribute('data-valid_until'),
                create_time: this.getAttribute('data-create_time'),
                edit_time: this.getAttribute('data-edit_time'),

                edit_url: this.getAttribute('data-edit_url'),
                delete_url: this.getAttribute('data-delete_url'),
            };

            ViewModalButtons(prefixData);
            showViewModal(prefixData);
        });
    });
});