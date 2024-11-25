<div class="overflow-hidden shadow"> 
    <div class="flex justify-between items-center mb-4 ms-3 me-4">
        <!-- Show Entries -->
        <div class="flex items-center">
            <label for="perPage" class="mr-2 text-sm text-gray-700 dark:text-gray-300">Show</label>
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
            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 mr-2">entries</span>
        </div>
    
        <!-- Search and Clear -->
        <div class="max-w-md w-full">
            <label for="default-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input 
                    type="search" 
                    id="default-search" 
                    x-ref="searchInput"
                    class="block w-full p-4 pl-10 pr-28 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Search"
                />
                <!-- Tombol Search -->
                <button 
                    type="button" 
                    wire:click="$set('search', $refs.searchInput.value)" 
                    class="rounded-full text-white absolute right-16 mr-1.5 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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
    </div>

    <!-- Tabel Data -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[70vh] overflow-y-auto">
        <table class="table-auto min-w-full divide-y divide-gray-200 dark:divide-gray-600 text-center" id="users_table" wire:poll.10s="autoUpdate">
            <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                        <button type="button" wire:click="sortBy('id')" class="flex items-center justify-center w-full focus:outline-none">
                            <span>ID</span>
                            @if ($sortField === 'id')
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
                    </th>
            
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                        <button type="button" wire:click="sortBy('name')" class="flex items-center justify-center w-full focus:outline-none">
                            <span>Name</span>
                            @if ($sortField === 'name')
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
                    </th>
            
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                        <button type="button" wire:click="sortBy('username')" class="flex items-center justify-center w-full focus:outline-none">
                            <span>Username</span>
                            @if ($sortField === 'username')
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
                    </th>
            
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                        <button type="button" wire:click="sortBy('email')" class="flex items-center justify-center w-full focus:outline-none">
                            <span>Email</span>
                            @if ($sortField === 'email')
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
                    </th>
            
                    <!-- Kolom Role -->
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">
                        <button type="button" wire:click="sortBy('role_id')" class="flex items-center justify-center w-full focus:outline-none">
                            <span>Role</span>
                            @if ($sortField === 'role_id')
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
                    </th>
                    
                    <th scope="col" class="p-4 text-sm font-medium text-gray-500 uppercase dark:text-gray-400 text-center align-middle">Action</th>
                </tr>
            </thead>
            
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-100 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="p-1 text-sm font-normal text-gray-500 dark:text-gray-400 text-center">{{ $user->id }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center">{{ $user->name }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center">{{ $user->username }}</td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center">
                            <span class="flex items-center">
                                <span class="mr-1.5">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z" />
                                </svg>
                                @endif
                            </span>
                        </td>
                        <td class="py-3 text-sm font-normal text-gray-500 dark:text-gray-400 text-center">
                            {{ $user->role->role ?? 'N/A' }}
                        </td>
                        <td class="p-3 space-x-2 whitespace-nowrap text-center">
                            <button class="read-more-btn text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-username="{{ $user->username }}"
                                data-email="{{ $user->email }}"
                                data-email-verified-at="{{ $user->email_verified_at ?? 'N/A' }}"
                                data-role="{{ $user->role->role ?? 'N/A' }}"
                                data-created-at="{{ $user->created_at }}"
                                data-updated-at="{{ $user->updated_at }}"
                                data-avatar="{{ env('APP_URL') . 'storage/' . ($user->avatar ?? 'no_image.png') }}">
                                Read More
                            </button>
                            <button class="edit-user-btn text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-username="{{ $user->username }}"
                                data-email="{{ $user->email }}"
                                data-role_id="{{ $user->role->id ?? 'N/A' }}"
                                data-avatar="{{ env('APP_URL') . 'storage/' . ($user->avatar ?? 'no_image.png') }}">
                                Edit
                            </button>
                            <button class="delete-user-btn text-white bg-red-600 hover:bg-red-650 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-username="{{ $user->username }}"
                                data-email="{{ $user->email }}"
                                data-role_name="{{ $user->role->role ?? 'N/A' }}"
                                data-avatar="{{ env('APP_URL') . 'storage/' . ($user->avatar ?? 'no_image.png') }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-1.5 px-4 text-center text-sm text-gray-500 dark:text-gray-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4 ml-4 mr-4">
        {{ $users->links() }}
    </div>
</div>
