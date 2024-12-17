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
            <input type="hidden" name="actionUrl" x-model="$store.deleteModal.data.actionUrl">

            <div class="md:col-span-2">
                <label class="block font-medium text-gray-900 dark:text-white mb-3 text-lg">
                    Do you really want to delete <span class="font-bold" x-text="$store.deleteModal.data.col_01"></span>? Please confirm.<br>
                </label>
            </div>
        </form>

        <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
            <button type="button" @click="$store.deleteModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            <button type="button" @click="submitDeleteForm" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
        </div>
    </div>    
</div>