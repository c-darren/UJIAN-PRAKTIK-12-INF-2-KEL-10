<div
    x-show="openJoinModalState" 
    x-cloak 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    @click.away="closeJoinModal">
    
    <div @click.stop class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Konfirmasi</h3>
            <button @click="closeJoinModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form @submit.prevent="submitForm" x-ref="joinForm" id="joinForm" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="joinData.id">
            <div class="p-4 text-lg dark:text-white">
                Apakah Anda yakin ingin bergabung ke kelas
                <span x-text="joinData.class_name" class="font-bold"></span>
                ?
            </div>
            <div class="flex justify-end">
                <button type="button" id="cancelButton" @click="closeJoinModal" class="mr-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Tidak</button>
                <button type="submit" class="update-button bg-sky-600 text-white px-4 py-2 rounded-md">Ya</button>
            </div>
        </form>
    </div>
</div>