@section('title', $page_title)

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <div id="info" class="flex items-center p-4 mb-2 mt-2 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div class="ms-3 text-sm font-medium">
                        Table data will be updated every 10 seconds.
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#info" aria-label="Close">
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
        createModal: { open: false },
        deleteModal: { open: false }
    }
}" class="relative">
    <div class="flex flex-col py-1">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                @livewire('classroom.class-list-student-table', ['masterClass_id' => $masterClass_id, 'classList_id' => $classList_id, 'classListName' => $classList->class_name])
            </div>
        </div>
    </div>

    {{-- Create Modal Button --}}
    <button @click="$store.createModal.show()" id="showCreateModal" class="hidden"></button>

    {{-- Confirm Modal --}}
    @include('dashboard.classroom.masterclass.manage.class_list_student.confirm_modal')

</div>

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/manage_class_list/class_list_student/create_and_delete.js') }}" defer></script>
@endsection