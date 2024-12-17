<div
    x-show="$store.editModal.open" 
    x-data="editModalData()"
    x-cloak 
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    @keydown.escape.window="$store.editModal.close()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90">
    
    <div @click.away="$store.editModal.close()" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Topik</h3>
            <button @click="$store.editModal.close()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form x-ref="editForm" id="editForm" class="space-y-4" @keydown.enter.prevent="submitEditForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="actionUrl" x-model="$store.editModal.data.actionUrl">
            <div>
                <label for="edit_topic_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Topik</label>
                <input 
                    type="text" 
                    name="topic_name" 
                    id="edit_topic_name" 
                    x-model="$store.editModal.data.col_01" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" 
                    required
                />
            </div>
            <div class="flex justify-end">
                <button 
                    type="button" 
                    @click="$store.editModal.close()" 
                    class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
                <button 
                    type="button"
                    @click="submitEditForm"
                    id="submitEdit" 
                    class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>