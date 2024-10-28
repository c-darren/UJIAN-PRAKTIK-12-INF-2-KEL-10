<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                @if(session('messageError'))
                <script>
                    Notiflix.Report.failure(
                        {!! json_encode(session('messageError.title')) !!}, 
                        {!! json_encode(session('messageError.message')) !!},
                        "Okay"
                    );
                </script>
                @endif
            
                <div id="info_creator_id_NA" class="flex items-center p-4 mb-2 mt-2 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div class="ms-3 text-sm font-medium">
                        <span class="font-semibold">N/A:</span>
                        No data is available. This may be caused by the absence of related users or because this data hasn't been edited yet.
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#info_creator_id_NA" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex flex-col py-1">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="table-auto min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="roles_table">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <span class="flex items-center">Role
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <span class="flex items-center">Description
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach ($roles as $role)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 break-all">{{ $role->role }}</td>
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">{{ $role->description::limit(50) }}</td>
                            <td class="p-4 space-x-2 whitespace-nowrap">
                                <button data-modal-target="modal_view" data-modal-toggle="modal_view" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    data-role-id="{{ $role->id }}"
                                    data-role-name="{{ $role->role }}"
                                    data-role-description="{{ $role->description }}"
                                    data-edit_url="{{ role('admin.authentication.role.edit', $role->id) }}"
                                    data-delete_url="{{ role('admin.authentication.role.destroy', $role->id) }}"
                                >Read More</button>
                                <a href="{{ role('admin.authentication.role.edit', $role->id) }}" class="text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-4 py-2 text-center me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                                <button data-modal-target="deleteModal" data-modal-toggle="deleteModal" data-delete_role_name="{{ $role->role }}" data-delete_url="{{ role('admin.authentication.role.destroy', $role->id) }}" class="px-4 py-2 bg-red-600 text-white rounded-full" onclick="showDeleteModal(this)">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>


<!-- View modal -->
<div id="modal_view" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative p-2 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 dark:text-white">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold break-all" id="role_name"></h3>    
                {{-- <button class="rounded-full text-white px-4 py-2 ms-auto" id="role_status">
                </button> --}}
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal_view">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="sm:flex sm:space-x-2 p-3">
                <div class="mb-4">
                    <p class="text-sm text-gray-400">role ID:</p>
                    <p id="role_id"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-400">Description:</p>
                    <p id="role_description"></p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex justify-between mt-4 sm:mt-6 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <a id="modal_view_edit_button" class="text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-4 py-2 text-center me-2 mb-2 dark:focus:ring-yellow-900" >Edit</a>
                <button id="modal_view_delete_button" data-modal-target="deleteModal" data-modal-toggle="deleteModal" class="px-4 py-2 bg-red-600 text-white rounded-full" onclick="showDeleteModal(this)">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div id="deleteModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <button type="button" id="close_delete_modal" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="mb-4 text-gray-500 dark:text-gray-300 break-words">Are you sure you want to delete 
                <span id="display_role_name_delete">This</span>
             role? Please enter the role to confirm.</p>            
            <!-- role Input -->
            <div class="mb-4">
                <input type="text" id="confirm_role" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Copy/retype the role to continue the action">
            </div>
            
            <div class="flex justify-center items-center space-x-4">
                <button data-modal-toggle="deleteModal" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    No, cancel
                </button>
                <button type="button" id="submit_delete_button" onclick="confirmDelete(this)" data-csrf="{{ csrf_token() }}" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                    Yes, I'm sure
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Toggle Script -->
<script>
    window.roles = @json($allRoles);
    window.groups = @json($allGroups);
</script>
<script type="text/javascript" src="{{ asset('js/authentication/role/view_paginate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/role/view_modals.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/role/delete.js') }}"></script>