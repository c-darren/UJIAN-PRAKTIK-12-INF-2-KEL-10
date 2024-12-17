{{-- Modal Create Topik --}}
<div x-show="$store.createModal.open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
    @keydown.escape.window="$store.createModal.close()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90">
    <div @click.away="$store.createModal.close()" class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-1/2">
        <div class="flex justify-between items-center p-4 border-b dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Daftar Hadir</h3>
            <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="createForm" action="{{ route('classroom.attendance.store', [$masterClass_id, $classList->id]) }}" method="POST" class="p-4">
            @csrf
            <div class="mb-2">
                <label for="topic_id" class="block text-gray-700 dark:text-gray-300 mb-2">Nama Topik</label>
                <select name="topic_id" id="topic_id" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-white">
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->topic_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="attendee_date" class="block text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                <input type="datetime-local" name="attendee_date" id="attendee_date" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-white">
            </div>
            <div class="mb-2">
                <label for="description" class="block text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <input type="text" placeholder="Description, Not required" name="description" id="description" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-white">
            </div>
            <div class="flex justify-end">
                <button type="button" @click="$store.createModal.close()" class="mr-2 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" id="submitCreate" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-600">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>