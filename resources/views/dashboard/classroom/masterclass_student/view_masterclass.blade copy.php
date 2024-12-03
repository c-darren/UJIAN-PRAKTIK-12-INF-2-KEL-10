<div x-data="{
    $store: { 
        readmoreModal: { open: false, data: {} },
        createModal: { open: false },
        editModal: { open: false },
        deleteModal: { open: false }
    },
    open: false, 
    data: {}, 
    showModal(tableData) { 
        this.data = tableData; 
        this.open = true; 
    }
    }"
    @keydown.escape.window="open = false"
    class="relative">
    <div class="flex flex-col py-1">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
            </div>
        </div>
    </div>
    <!-- Create Modal Button -->
    <button @click="$store.createModal.show()" id="showCreateModal" class="hidden"></button>

    <!-- Create Modal -->
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">New Master Class</h3>
                    <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="createForm" x-ref="createForm" @keydown.enter.prevent="submitCreateForm"> <!-- Sesuaikan ID dengan yang ada di JavaScript -->
                    @csrf
                    <!-- Master Class Name -->
                    <div class="sm:col-span-2">
                        <label for="master_class_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Master Class Name</label>
                        <input type="text" name="master_class_name" id="master_class_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 12-4">
                    </div>

                    <!-- Master Class Code -->
                    <div class="sm:col-span-2">
                        <label for="master_class_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Master Class Code</label>
                        <input type="text" name="master_class_code" id="master_class_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 12INF2">
                    </div>

                    <!-- Academic Year -->
                    <div class="sm:col-span-2">
                        <label for="academic_year_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <!-- Assume academic_years are passed to the view -->
                            <option value="">Select Academic Year</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Active">Active</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.createModal.close()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="resetCreateForm()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Reset</button>
                <button @click="submitCreateForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add </button>
            </div>
        </div>
    </div>

    
    <!-- Read More Modal -->
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="$store.readmoreModal.data.col_01 + ' Information'"></h3>
                    <button @click="$store.readmoreModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <div class="sm:flex sm:space-x-2 p-3 overflow-y-auto">
                    <div class="sm:w-1/2 mr-5">
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">ID:</p>
                            <p x-text="$store.readmoreModal.data.id" class="dark:text-white"></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Master Class Name:</p>
                            <p x-text="$store.readmoreModal.data.col_01" class="dark:text-white"></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Master Class Code:</p>
                            <p x-text="$store.readmoreModal.data.col_02" class="dark:text-white"></p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Academic Year:</p>
                            <p x-text="$store.readmoreModal.data.col_03" class="dark:text-white"></p>
                        </div>
                    </div>
                    <div class="sm:w-1/2">
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Status:</p>
                            <p x-text="$store.readmoreModal.data.col_04"
                            :class="$store.readmoreModal.data.col_04 == 'Active' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                            </p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Created At:</p>
                            <p x-text="$store.readmoreModal.data.col_05" class="dark:text-white"></p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-white">Updated At:</p>
                            <p x-text="$store.readmoreModal.data.col_06" class="dark:text-white"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.readmoreModal.close()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="$store.editModal.data.col_01 + ' Update Form'"></h3>
                    <button @click="$store.editModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="editForm" x-ref="editForm" @keydown.enter.prevent="submitEditForm">
                    @csrf
                    <div class="sm:col-span-2">
                        <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID</label>
                        <input x-model="$store.editModal.data.id" type="number" name="id" id="id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="id" readonly>
                    </div>

                    <!-- Master Class Name -->
                    <div class="sm:col-span-2">
                        <label for="master_class_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Master Class Name</label>
                        <input x-model="$store.editModal.data.col_01" type="text" name="master_class_name" id="master_class_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 12-4">
                    </div>

                    <!-- Master Class Code -->
                    <div class="sm:col-span-2">
                        <label for="master_class_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Master Class Code</label>
                        <input x-model="$store.editModal.data.col_02" type="text" name="master_class_code" id="master_class_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 12INF2">
                    </div>

                    <!-- Academic Year -->
                    <div class="sm:col-span-2">
                        <label for="academic_year_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Year</label>
                        <select x-model="$store.editModal.data.col_03" name="academic_year_id" id="academic_year_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <!-- Assume academic_years are passed to the view -->
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select x-model="$store.editModal.data.col_04" name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Active">Active</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.editModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="submitEditForm" class="bg-yellow-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="$store.deleteModal.data.col_01 + ' Delete Form'"></h3>
                    <button @click="$store.deleteModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="deleteForm" x-ref="deleteForm">
                @csrf
                <input type="hidden" id="id" name="id" x-model="$store.deleteModal.data.id" readonly>

                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-900 dark:text-white mb-3 text-lg">
                        DO you really want to delete <span class="bold" x-text="$store.deleteModal.data.col_01"></span>? Please confirm.<br>
                    </label>
                </div>
            </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button type="button" @click="$store.deleteModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="button" @click="submitDeleteForm" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>    
    </div>

</div>

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/masterclass/read_more.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/masterclass/create.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/masterclass/edit.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/masterclass/delete.js') }}"></script> 
@endsection
