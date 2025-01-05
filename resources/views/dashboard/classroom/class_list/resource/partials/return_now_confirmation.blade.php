<template x-teleport="body">
    <div x-show="returnNowModalOpen"
        x-transition
        @click.away="returnNowModalOpen = false"
        class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="returnNowModalOpen = false"></div>

            <div class="relative inline-block p-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-700 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="p-6 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Konfirmasi Pengembalian
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Apakah Anda yakin ingin mengembalikan tugas ini sekarang?
                    </p>
                </div>

                <div class="flex justify-end p-6 space-x-2 border-t border-gray-200 dark:border-gray-600">
                    <button @click="confirmReturn()" 
                            class="px-4 py-2 text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                        Ya, Kembalikan
                    </button>
                    <button @click="returnNowModalOpen = false" 
                            class="px-4 py-2 text-gray-500 bg-white rounded-lg border hover:bg-gray-100">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>