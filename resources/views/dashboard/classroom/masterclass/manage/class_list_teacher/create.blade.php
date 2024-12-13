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
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add Teacher</h3>
                <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('class_lists.teacher.store', [$masterClass_id, $classList_id]) }}" method="POST" class="space-y-4" id="createForm" x-ref="createForm" @keydown.enter.prevent="submitCreateForm" @submit.prevent="submitCreateForm">
            @csrf
            <div class="col-span-2">
                <label for="teacher_id" class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-1">Select Teacher Name / ID</label>
                {{-- <select name="teacher_id" id="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">Select teacher</option>
                    @foreach($available_teachers as $available_teachers)
                        <option value="{{ $available_teachers->id }}" id="{{ $available_teachers->id }}">{{ $available_teachers->name }}</option>
                    @endforeach
                </select> --}}
                <input list="available_teacher" 
                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                id="teacher_id"
                placeholder="Type ID or Name"
                name="teacher_id">
                <datalist id="available_teacher">
                    @foreach($available_teachers as $available_teachers)
                        <option data-id="{{ $available_teachers->id }}" value="{{ $available_teachers->id }} - {{ $available_teachers->name }}" id="{{ $available_teachers->id }}"></option>
                    @endforeach
                </datalist>
            </div>
            
            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.createModal.close()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="reset" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Reset</button>
                <button type="submit" id="submit_form" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add Teacher</button>
            </div>
        </form>
        
    </div>
</div>