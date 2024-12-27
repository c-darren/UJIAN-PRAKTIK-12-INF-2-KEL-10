<div x-data="{
    show() {
        this.$refs.modal.classList.remove('hidden');
    },
    hide() {
        this.$refs.modal.classList.add('hidden');
    }
}" x-ref="modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Modal backdrop -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
             @click="hide()"></div>

        <!-- Modal panel -->
        <div class="relative z-50 w-full max-w-md p-4 overflow-hidden bg-white rounded-lg dark:bg-gray-700"
             @click.stop>
            <div class="flex justify-between items-start p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Kembalikan Nilai
                </h3>
                <button type="button" 
                        @click="hide()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Return options -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Opsi Pengembalian
                    </label>
                    <select x-model="$store.submissions.returnType"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="now">Kembalikan Sekarang</option>
                        <option value="scheduled">Jadwalkan Pengembalian</option>
                    </select>
                </div>

                <!-- Scheduled return datetime -->
                <div x-show="$store.submissions.returnType === 'scheduled'">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Waktu Pengembalian
                    </label>
                    <input type="datetime-local" x-model="$store.submissions.scheduledTime"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                </div>
            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" @click="$store.submissions.submitGrade()" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Konfirmasi
                </button>
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" @click="hide()">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
