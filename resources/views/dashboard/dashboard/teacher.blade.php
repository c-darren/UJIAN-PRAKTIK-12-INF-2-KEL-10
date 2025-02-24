<div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
</div>
<div class="grid grid-cols-1 px-4 xl:grid-cols-2 xl:gap-4">
    <!-- Archived Classes -->
    <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 xl:mb-0" x-data="{ search: '' }">
        <h2 class="text-lg font-semibold mb-2 dark:text-white text-gray-900">Archived Classes</h2>
        <!-- Input Pencarian -->
        <div class="mb-4">
            <input 
                type="text" 
                placeholder="Search Archived Classes..." 
                x-model="search" 
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            />
        </div>
        <!-- Grid Kelas -->
        <div class="mt-2 grid grid-cols-[repeat(auto-fill,_minmax(160px,_1fr))] gap-4 mr-4 ml-4 sm:mt-3 lg:mt-3 xl:mt-6 max-h-[70vh] overflow-y-auto scrollbar-style-1">
            @forelse($archivedClasses as $class)
                @foreach($class['class_lists'] as $list)
                    <div 
                        class="max-w-[160px] bg-white dark:bg-gray-800 rounded-lg border border-gray-200 shadow-lg dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-all duration-500 flex flex-col"
                        x-show="(
                            '{{ strtolower($list['class_name']) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($list['subject']) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($list['enrollment_status']) }}'.includes(search.toLowerCase())
                        )"
                    >
                        <a href="{{ route('classroom.index', ['masterClass_id' => $class['master_class_id'], 'class_id' => $list['class_list_id']]) }}" 
                           class="flex flex-col h-full">
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex flex-wrap justify-between items-start mb-4">
                                    <h5 class="text-xl font-bold text-gray-900 dark:text-white flex-1">
                                        {{ $list['class_name'] }}
                                    </h5>
                                </div>
                                <span class="inline-block bg-blue-500 text-white text-sm font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                                    {{ $list['subject'] }}
                                </span>
                            </div>
                            <div class="mt-auto p-3 text-sm font-medium border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                <p class="truncate text-gray-700 dark:text-gray-300">
                                    {{ $list['master_class_name'] }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach

            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-center text-gray-500 dark:text-gray-300">No archived classes found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Active Classes -->
    <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 xl:mb-0" x-data="{ search: '' }">
        <h2 class="text-lg font-semibold mb-2 dark:text-white text-gray-900">Active Classes</h2>
        <!-- Input Pencarian -->
        <div class="mb-4">
            <input 
                type="text" 
                placeholder="Search Active Classes..." 
                x-model="search" 
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            />
        </div>
        <!-- Grid Kelas -->
        <div class="mt-2 grid grid-cols-[repeat(auto-fill,_minmax(160px,_1fr))] gap-4 mr-4 ml-4 sm:mt-3 lg:mt-3 xl:mt-6 max-h-[70vh] overflow-y-auto scrollbar-style-1">
            @forelse($activeClasses as $class)
                @foreach($class['class_lists'] as $list)
                    <div 
                        class="max-w-[160px] bg-white dark:bg-gray-800 rounded-lg border border-gray-200 shadow-lg dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-all duration-500 flex flex-col"
                        x-show="(
                            '{{ strtolower($list['class_name']) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($list['subject']) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($list['enrollment_status']) }}'.includes(search.toLowerCase())
                        )"
                    >
                        <a href="{{ route('classroom.index', ['masterClass_id' => $class['master_class_id'], 'class_id' => $list['class_list_id']]) }}" 
                           class="flex flex-col h-full">
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex flex-wrap justify-between items-start mb-4">
                                    <h5 class="text-xl font-bold text-gray-900 dark:text-white flex-1">
                                        {{ $list['class_name'] }}</h5>
                                </div>
                                <span class="inline-block bg-blue-500 text-white text-sm font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                                    {{ $list['subject'] }}
                                </span>
                                <span class="inline-block text-white text-sm font-semibold py-1.5 px-4 rounded-full 
                                    {{ $list['enrollment_status'] === 'Open' ? 'bg-green-500 dark:bg-green-600' : 'bg-red-500 dark:bg-red-600' }}">
                                    {{ $list['enrollment_status'] }}
                                </span>
                            </div>
                            <div class="mt-auto p-3 text-sm font-medium border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                <p class="truncate text-gray-700 dark:text-gray-300">
                                    {{ $list['master_class_name'] }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach

            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-center text-gray-500 dark:text-gray-300">No active classes found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>