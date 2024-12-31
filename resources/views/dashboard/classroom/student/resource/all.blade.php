<div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 shadow dark:border-gray-700">
    <div class="flex justify-between mb-2">
        <h1 class="sm:text-xl text-lg font-medium title-font text-gray-900 dark:text-white">Guru: {{ count($teachers) }} orang</h1>
        <select onchange="window.location.href='?sort=' + this.value" class="bg-gray-100 dark:bg-gray-600 text-sm rounded-full dark:text-white font-semibold">
            <option value="asc" {{ request('sort', 'asc') === 'asc' ? 'selected' : '' }}>A-Z</option>
            <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>Z-A</option>
        </select>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($teachers as $teacher)
            <div class="bg-gray-100 dark:bg-gray-600 dark:text-gray-300 rounded-xl flex p-4 h-full items-center">
                <svg fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                    <path d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                </svg>
                <span class="title-font font-medium">{{ $teacher->name }}</span>
            </div>
        @endforeach
    </div>

    <h1 class="sm:text-xl text-lg font-medium title-font text-gray-900 mt-8 mb-2 dark:text-white">Peserta Didik: {{ count($students) }} orang</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($students as $student)
            <div class="bg-gray-100 dark:bg-gray-600 dark:text-gray-300 rounded-xl flex p-4 h-full items-center">
                <svg fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                    <path d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                </svg>
                <span class="title-font font-medium">{{ $student->name }}</span>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>