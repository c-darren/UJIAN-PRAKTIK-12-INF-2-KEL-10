<div
    x-show="openEditModalState" 
    x-cloak 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    @click.away="closeEditModal">
    
    <div @click.stop class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Class List</h3>
            <button @click="closeEditModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form @submit.prevent="submitForm" x-ref="editForm" id="editForm" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="editData.id">
            <div>
                <label for="edit_class_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Name</label>
                <input type="text" name="class_name" id="edit_class_name" x-model="editData.class_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>
            <div>
                <label for="edit_subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                <select name="subject_id" id="edit_subject_id" x-model="editData.subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">Select a subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit_enrollment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enrollment Status</label>
                <select name="enrollment_status" id="edit_enrollment_status" x-model="editData.enrollment_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">Select status</option>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" id="cancelButton" @click="closeEditModal" class="mr-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Cancel</button>
                <button type="submit" class="update-button bg-blue-600 text-white px-4 py-2 rounded-md">Update</button>
            </div>
        </form>
    </div>
</div>