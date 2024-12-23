{{-- Modal Create Assignment --}}
<div x-show="$store.createAssignmentModal.open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
    @keydown.escape.window="$store.createAssignmentModal.close()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90">
    <div @click.away="$store.createAssignmentModal.close()" class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-11/12 max-w-4xl" x-data="assignmentTable()">
        <div class="flex justify-between items-center p-4 border-b dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Tugas</h3>
            <button @click="$store.createAssignmentModal.close()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="createAssignmentForm" action="{{ route('classroom.resources.store', [$masterClass_id, $classList->id, 'assignment']) }}" method="POST" enctype="multipart/form-data" class="p-4">
            @csrf
            <div class="flex space-x-4">
                <!-- Left 70% -->
                <div style="width: 70%;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Tugas</label>
                        <input type="text" name="assignment_name" id="assignment_name" x-model="assignmentForm.assignment_name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="description" id="assignment_description" x-model="assignmentForm.description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lampiran</label>
                        <input type="file" name="attachment[]" id="attachment" multiple @change="handleAssignmentFileUpload" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                    </div>
                </div>
                <!-- Right 30% -->
                <div style="width: 30%;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Topik</label>
                        <select name="topic_id" id="assignment_topic_id" x-model="assignmentForm.topic_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                            <option value="">Pilih Topik</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->topic_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_date" id="assignment_start_date" x-model="assignmentForm.start_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenggat Waktu</label>
                        <input type="datetime-local" name="end_date" id="assignment_end_date" x-model="assignmentForm.end_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" name="accept_late_submissions" id="accept_late_submissions" x-model="assignmentForm.accept_late_submissions" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="accept_late_submissions" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Terima Pengumpulan Terlambat
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" id="submitAssignmentCreate" @click="submitAssignmentForm" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>