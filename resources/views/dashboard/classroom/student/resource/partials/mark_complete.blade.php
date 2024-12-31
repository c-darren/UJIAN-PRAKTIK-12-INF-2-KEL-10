<template x-teleport="body">
    <div x-show="showMarkCompleteModal"
         x-transition
         @click.away="showMarkCompleteModal = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        Konfirmasi Penyelesaian
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menandai tugas ini sebagai selesai?
                    </p>
                </div>

                <div class="flex justify-end p-4 border-t dark:border-gray-700">
                    <button @click="markComplete()"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Ya, Tandai Selesai
                    </button>
                    <button @click="showMarkCompleteModal = false"
                            class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>