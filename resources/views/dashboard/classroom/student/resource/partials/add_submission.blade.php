<template x-teleport="body">
    <div x-show="showAddSubmissionModal" 
         x-transition
         @click.away="showAddSubmissionModal = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Tugas</h3>
                </div>
                
                <form @submit.prevent="submitAssignment" id="submissionForm">
                    <div class="p-4">
                        <!-- Input file -->
                        <input type="file" multiple @change="handleFiles"
                               class="block w-full text-sm border rounded-lg cursor-pointer dark:text-gray-400 dark:border-gray-600">
                        
                        <!-- Tampilkan daftar file yang akan dihapus -->
                        <div x-show="deleteAttachmentsList.length > 0" class="mt-4">
                            <p class="text-sm text-red-600 dark:text-red-400">File yang akan dihapus:</p>
                            <ul class="mt-2 space-y-1">
                                <template x-for="path in deleteAttachmentsNames" :key="path">
                                    <li x-text="path.split('/').pop()" class="text-sm text-gray-600 dark:text-gray-400"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                
                    <div class="flex justify-end p-4 border-t dark:border-gray-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kirim
                        </button>
                        <button type="button" @click="showAddSubmissionModal = false"
                                class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>