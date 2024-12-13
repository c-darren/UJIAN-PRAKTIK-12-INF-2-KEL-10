<div class="overflow-hidden shadow">

    <div class="flex flex-wrap justify-between items-center mb-4 ms-3 me-4 gap-4">
        <!-- Show Entries -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <label for="perPage" class="text-sm text-gray-700 dark:text-gray-300">Show</label>
            <select 
                id="perPage" 
                wire:change="updatePerPage($event.target.value)"
                class="p-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <span class="text-sm text-gray-700 dark:text-gray-300">entries</span>
        </div>
    
        <!-- Search and Clear -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <div class="relative flex-1">
                <input 
                    type="search" 
                    id="default-search" 
                    x-ref="searchInput" 
                    class="block w-full p-4 pl-10 pr-28 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Search by Names or Codes"
                    wire:model.debounce.500ms="search"
                />
                <!-- Tombol Search -->
                <button 
                    type="button" 
                    wire:click="$set('search', $refs.searchInput.value)" 
                    class="rounded-full text-white absolute right-16 mr-1.5 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    id="search-button"
                >
                    <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>                   
                </button>
                <!-- Tombol Clear -->
                <button 
                    type="button" 
                    wire:click="$set('search', ''); $nextTick(() => $refs.searchInput.value = '')"
                    class="rounded-full text-black absolute right-2 bottom-2 bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium text-sm px-4 py-2 dark:bg-amber-500 dark:hover:bg-amber-600 dark:focus:ring-orange-800"
                >
                    <svg class="w-5 h-5 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </button>
            </div>
        </div>
    
        <!-- Dropdown untuk Academic Year -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <select 
                wire:change="updateAcademicYear($event.target.value)"
                class="p-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="" hidden>Select Academic Year</option>
                <option value="">All Academic Year</option>
                @foreach ($academicYears as $id => $year)
                    <option value="{{ $id }}">{{ $year }}</option>
                @endforeach
            </select>
            {{-- <option value="1">2022-2023</option> --}}
        </div>
    
        <!-- Dropdown untuk Status -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <select 
                wire:change="updateStatus($event.target.value)" 
                class="p-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="" hidden>Select Status</option>
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="archived">Archived</option>
            </select>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[70vh] overflow-y-auto">
        <table class="table-auto min-w-full divide-y divide-gray-200 dark:divide-gray-600 text-center" id="roles_table" wire:poll.10s="autoUpdate">
            <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                <tr>
                    @foreach (['id' => 'ID', 'master_class_name' => 'Master Class Name', 'master_class_code' => 'Code', 'academic_year' => 'Academic Year', 'status' => 'Status', 'actions' => 'Actions'] as $field => $label)
                        <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                            @if ($field !== 'actions')
                                <button type="button" wire:click="sortBy('{{ $field }}')" class="flex items-center justify-center w-full focus:outline-none">
                                    <span>{{ $label }}</span>
                                    @if ($sortField === $field)
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 ms-1 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    @endif
                                </button>
                            @else
                                <span>{{ $label }}</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($records as $record)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-100 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="p-1 text-sm font-normal text-gray-500 dark:text-gray-400 text-center break-all">{{ $record->id }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center break-all">{{ $record->master_class_name }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center break-all">{{ $record->master_class_code }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center break-all">{{ $record->academic_year_relation->academic_year }}</td>
                        <td class="py-3 text-sm font-normal text-center break-all
                            {{ $record->status == 'Active' ? 'text-green-500' : 'text-red-500' }}"
                            >{{ $record->status }}
                        </td>

                        <td class="p-3 flex justify-between items-center whitespace-nowrap">
                            <button
                                class="read-more-data-btn text-white sm:mr-2 bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 dark:focus:ring-blue-900"
                                data-id="{{ $record->id }}" data-col_01="{{ $record->master_class_name }}"
                                data-col_02="{{ $record->master_class_code }}"
                                data-col_03="{{ $record->academic_year }}"
                                data-col_04="{{ $record->status }}"
                                data-col_05="{{ $record->created_at }}"
                                data-col_06="{{ $record->updated_at }}">
                                Read More
                            </button>
                            <a
                                class="manage-data-btn text-white sm:mr-2 bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-3 py-2 dark:focus:ring-green-900"
                                href="{{ route('classroom.masterClass.manage.index', $record->id) }}"
                                data-id="{{ $record->id }}">
                                Manage
                            </a>
                            @if($record->academic_year_status == 'Active')
                            <button
                                class="edit-data-btn text-white sm:mr-2 bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 dark:focus:ring-blue-900"
                                data-id="{{ $record->id }}" data-col_01="{{ $record->master_class_name }}"
                                data-col_02="{{ $record->master_class_code }}"
                                data-col_03="{{ $record->academic_year }}"
                                data-col_04="{{ $record->status }}">
                                Edit
                            </button>
                            @endif
                            <button
                                class="delete-data-btn text-white sm:mr-2 bg-red-600 hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 dark:focus:ring-blue-900"
                                data-id="{{ $record->id }}" data-col_01="{{ $record->master_class_name }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-1.5 px-4 text-center text-sm text-gray-500 dark:text-gray-400">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Links -->
    <div class="mt-4 ml-4 mr-4">
        {{ $records->links() }}
    </div>
</div>