{{-- Modal Create Topik --}}
<div x-show="$store.createModal.open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
    @keydown.escape.window="$store.createModal.close()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90">
    <div @click.away="$store.createModal.close()" class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center p-4 border-b dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Topik</h3>
            <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="createTopicForm" action="{{ route('classroom.topic.store', [$masterClass_id, $classList->id]) }}" method="POST" class="p-4">
            @csrf
            <div class="mb-4">
                <label for="create_topic_name" class="block text-gray-700 dark:text-gray-300 mb-2">Nama Topik</label>
                <input 
                    type="text" 
                    id="create_topic_name" 
                    name="topic_name"  
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-white"
                />
            </div>
            <div class="flex justify-end">
                <button type="button" @click="$store.createModal.close()" class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" id="submitCreate" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-600">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>