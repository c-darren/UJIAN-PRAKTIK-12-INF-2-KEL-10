<template x-teleport="body">
    <div
        @click="showDetailModal = false"
        x-show="showDetailModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black opacity-50"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div @click.stop class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Informasi</h3>
                    <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 space-y-3">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Topik:</span> {{ $topic_name }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Diposting:</span> {{ $formatted_start_date }}
                        </p>
                        
                        @if($resource_type == 'assignment')
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">Tenggat Waktu:</span> {{ $formatted_end_date }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">Terima Pengumpulan Terlambat:</span> 
                                <span class="@if($accept_late_submissions == 1) text-green-500 @else text-red-500 @endif">
                                    @if($accept_late_submissions == 1) Ya @else Tidak @endif
                                </span>
                            </p>
                        @endif

                        <hr class="dark:border-gray-700">
                        
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Dibuat:</span> {{ $created_at }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Dibuat oleh:</span> {{ $author }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Terakhir Diperbarui oleh:</span> {{ $editor }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Terakhir Diperbarui:</span> {{ $updated_at }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>