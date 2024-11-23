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
                        Table data will be updated every 10 seconds.
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
<div x-data="{
    $store: { 
        readmoreModal: { open: false, role: {} },
        createModal: { open: false },
        editModal: { open: false },
        deleteModal: { open: false }
    },
    open: false, 
    role: {}, 
    showModal(roleData) { 

        this.role = roleData; 
        this.open = true; 
    } 
    }"
    @keydown.escape.window="open = false"
    class="relative">
    <div class="flex flex-col py-1">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                @livewire('authentication.roles-table')
            </div>
        </div>
    </div>
    {{-- Create Modal Button --}}
    <button @click="$store.createModal.show()" id="showCreateModal" class="hidden"></button>

    {{-- Create Modal --}}
    <div x-show="$store.createModal.open"
        x-data="createModalData()"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.createModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        <div @click.away="$store.createModal.close()"
            class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">New Role</h3>
                    <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="createRoleForm" x-ref="createRoleForm">
                    @csrf
                    <!-- Role -->
                    <div class="sm:col-span-2">
                        <label for="roleName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role Name</label>
                        <input type="text" name="roleName" id="roleName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter prefix name" required>
                        <small class="text-red-500">Role must be unique.</small>
                    </div> 
                    
                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="desc" name="desc" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter description"></textarea>
                    </div>
                </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.createModal.close()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="resetCreateForm()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Reset</button>
                <button @click="submitCreateForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add Roles</button>
            </div>
        </div>
    </div>
    
    {{-- Read More Modal --}}
    <div x-show="$store.readmoreModal.open"
        x-data="{}"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.readmoreModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
    
        <div @click.away="$store.readmoreModal.close()"
            class="bg-white dark:bg-gray-800 w-full max-w-2xl p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Role Details</h3>
                    <button @click="$store.readmoreModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-400 dark:text-white">ID:</p>
                    <p x-text="$store.readmoreModal.role.id" class="dark:text-white"></p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-400 dark:text-white">Role Name:</p>
                    <p x-text="$store.readmoreModal.role.role_name" class="dark:text-white"></p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-400 dark:text-white">Description:</p>
                    <p x-text="$store.readmoreModal.role.desc" class="dark:text-white"></p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.readmoreModal.close()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="$store.editModal.open"
        x-data="editModalData()"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.editModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
    
        <div @click.away="$store.editModal.close()"
            class="bg-white dark:bg-gray-800 w-full max-w-2xl p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Role</h3>
                    <button @click="$store.editModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>

                
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="editRoleForm" x-ref="editRoleForm">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Role ID</label>
                        <input type="number" id="roleId" name="id" x-model="$store.editModal.role.id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Role Name</label>
                        <input type="text" id="roleName" name="roleName" x-model="$store.editModal.role.role_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea
                            name="roleDesc"
                            id="roleDesc"
                            rows="2"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter description"
                            x-model="$store.editModal.role.desc"
                        ></textarea>
                    </div>
                </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.editModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="submitEditForm" class="bg-yellow-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="$store.deleteModal.open"
        x-data="deleteModalData()"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.deleteModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        <div @click.away="$store.deleteModal.close()"
                class="bg-white dark:bg-gray-800 w-full max-w-md p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Role</h3>
                    <button @click="$store.deleteModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="deleteRoleForm" x-ref="deleteRoleForm">
                @csrf
                <input type="hidden" id="roleId" name="roleId" x-model="$store.deleteModal.role.id" readonly>
                <input type="hidden" id="originalroleName" name="originalroleName" x-model="$store.deleteModal.role.role_name" readonly>

                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-900 dark:text-white mb-3 text-lg">
                        Are you sure you want to delete role <span class="bold" x-text="$store.deleteModal.role.role_name"></span>?<br>Please enter the role name to confirm.
                    </label>
                    <input type="text" id="roleName" name="roleName" class="roleName-value bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.deleteModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="submitDeleteForm" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>    
    </div>

</div>

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/authentication/role/read_more.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/role/create.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/role/edit.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/role/delete.js') }}"></script>
@endsection