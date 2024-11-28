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
        readmoreModal: { open: false, subject: {} },
        createModal: { open: false },
        editModal: { open: false },
        deleteModal: { open: false }
    },
    open: false, 
    subject: {}, 
    showModal(subjectData) { 
        this.subject = subjectData; 
        this.open = true; 
    } 
    }"
    @keydown.escape.window="open = false"
    class="relative">
    <div class="flex flex-col py-1">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                @livewire('classroom.subject-table')
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">New Subject</h3>
                    <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="createSubjectForm" x-ref="createSubjectForm" @keydown.enter.prevent="submitCreateForm"> <!-- Sesuaikan ID dengan yang ada di JavaScript -->
                    @csrf
                    <!-- Subject -->
                    <div class="sm:col-span-2">
                        <label for="subject_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                        <input type="text" name="subject_name" id="subject_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., Math">
                        <small class="text-red-500">Subject must be unique.</small>
                    </div> 
                    
                </form>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.createModal.close()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="resetCreateForm()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Reset</button>
                <button @click="submitCreateForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add Subject</button>
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Academic Year Details</h3>
                    <button @click="$store.readmoreModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <div class="sm:flex sm:space-x-2 p-3 overflow-y-auto">
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">ID:</p>
                        <p x-text="$store.readmoreModal.subject.id" class="dark:text-white"></p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Subject Name:</p>
                        <p x-text="$store.readmoreModal.subject.subject_name" class="dark:text-white"></p>
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Subject</h3>
                    <button @click="$store.editModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="editsubjectForm" x-ref="editsubjectForm" @keydown.enter.prevent="submitEditForm">
                    @csrf
                    <input type="hidden" name="originalsubject" id="originalsubject" x-model="$store.editModal.originalsubject" readonly>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID</label>
                        <input type="number" id="subjectId" name="id" x-model="$store.editModal.subject.id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject Name</label>
                        <input type="text" id="subject_name" name="subject_name" x-model="$store.editModal.subject.subject_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Subject</h3>
                    <button @click="$store.deleteModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto" id="deletesubjectForm" x-ref="deletesubjectForm">
                @csrf
                <input type="hidden" id="subjectId" name="subjectId" x-model="$store.deleteModal.subject.id" readonly>
                <input type="hidden" id="originalsubject" name="originalsubject" x-model="$store.deleteModal.subject.subject_name" readonly>

                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-900 dark:text-white mb-3 text-lg">
                        Are you sure you want to delete subject <span class="bold" x-text="$store.deleteModal.subject.subject_name"></span>?<br>Please enter the subject to confirm.<br>
                        <span class="text-red-600">This action cannot be undone, all related data will be deleted.</span>
                    </label>
                    <input type="text" id="subjectInput($event)" name="subjectInput" class="subject-value bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" @keydown.enter.prevent="submitDeleteForm">
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
<script type="text/javascript" src="{{ asset('js/classroom/subject/read_more.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/subject/create.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/subject/edit.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/subject/delete.js') }}"></script> 
@endsection
