<div>    
    <div class="flex flex-wrap justify-between items-center mb-4 ms-3 me-4 gap-4">
        <button 
            @click="isGrid = !isGrid" 
            class="hidden md:inline-block mr-2 p-2 rounded-full bg-red-600 text-white hover:bg-red-700 dark:bg-red-650 dark:hover:bg-red-700 transition duration-500">
            <!-- Ikon Grid -->
            <svg x-show="isGrid" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Ikon List -->
            <svg x-show="!isGrid" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/>
            </svg>
        </button>

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
                    placeholder="Search by description or title"
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
        <br>
        <!-- Dropdown untuk Status -->
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <select 
                wire:change="updateStatus($event.target.value)" 
                class="p-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="all" @if($status === 'all') selected @endif>Tampilkan Semua</option>
                <option value="tugas" @if($status === 'tugas') selected @endif>Tugas</option>
                <option value="materi" @if($status === 'materi') selected @endif>Materi</option>
                <option value="deadline" @if($status === 'deadline') selected @endif>Tugas Melewati Deadline</option>
            </select>
        </div>
        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <select 
                wire:change="selectTopic($event.target.value)" 
                class="p-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="all" @if($status === 'all') selected @endif>Semua Topik</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" @if($status === $topic->topic_name) selected @endif>{{ $topic->topic_name }}</option>
                    @endforeach
            </select>
        </div>
        <button id="dropdownActionButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Buat <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
            </button>
            
            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
                  <li>
                    <a href="#" @click="$store.createModal.show()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buat Materi</a>
                  </li>
                  <li>
                    <a href="#" @click="$store.createAssignmentModal.show()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buat Tugas</a>
                  </li>
                </ul>
            </div>
        <!-- Show Entries -->
    </div>
    <div
    {{-- Untuk memperbesar width, atur pada _minmax --}}
    :class="isGrid ? 'mt-2 grid grid-cols-[repeat(auto-fill,_minmax(320px,_1fr))] gap-4 mr-4 ml-4 sm:mt-3 lg:mt-3 xl:mt-6' : 'flex flex-col gap-4'" 
    class="transition-all duration-1000 ease-in-out max-h-[70vh] overflow-y-auto scrollbar-style-1" wire:poll.10000ms>

    @forelse($resources as $resource)
        @php
            if ($resource->type === 'material') {
                $name = $resource->material_name;
                $author = $resource->author->name;
                $description = strtolower($resource->description);
                $route_show = route('classroom.resources.show', [$masterClass_id, $classList->id, 'material', $resource->id]);
                $route_destroy = route('classroom.resources.destroy', [$masterClass_id, $classList->id, 'material', $resource->id,]);
            } elseif ($resource->type === 'assignment') {
                $name = $resource->assignment_name;
                $author = $resource->author->name;
                $description = strtolower($resource->description);
                $route_show = route('classroom.resources.show', [$masterClass_id, $classList->id, 'assignment' ,$resource->id]);
                $route_destroy = route('classroom.resources.destroy', [$masterClass_id, $classList->id, 'assignment', $resource->id]);
            }
        @endphp

        <div 
            class="p-1 w-full transition-transform duration-700"
            x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-500 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4">
            
            <!-- Tambahkan pengaturan flex agar elemen tidak tumbang tindih -->
            <div 
                class="flex items-center border-gray-200 border p-3 rounded-lg hover:bg-gray-200 hover:dark:bg-gray-700 transition duration-500">
                
                <div class="border-2 border-dashed border-gray-600 dark:border-white rounded-full p-1.5 mr-2 left-1">
                    <svg class="rounded-full w-[31px] h-[31px] text-gray-800 dark:text-white" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        @if($resource->type === 'material')
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                  d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                        @elseif($resource->type === 'assignment')
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 5V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5M9 3v4a1 1 0 0 1-1 1H4m11.383.772 2.745 2.746m1.215-3.906a2.089 2.089 0 0 1 0 2.953l-6.65 6.646L9 17.95l.739-3.692 6.646-6.646a2.087 2.087 0 0 1 2.958 0Z"/>                                    
                        @endif
                    </svg>
                </div>
                
                <div class="flex-grow">
                    <!-- Container untuk Judul dan Badge -->
                    <div class="inline justify-between items-center overflow-hidden">
                        <h2 class="text-gray-900 title-font font-medium dark:text-white">{{ $name }}</h2>
                        @if($resource->type === 'assignment' && now()->gt($resource->end_date))
                            <span class="inline-flex items-center mb-1 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                Deadline Terlewati
                            </span>
                        @elseif($resource->type === 'assignment')
                            <span class="inline-flex items-center mb-1 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Tugas
                            </span>
                        @elseif($resource->type === 'material')
                            <span class="inline-flex items-center mb-1 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                Materi
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">{{ $author }} | {{ $resource->created_at->format('d M Y') }}</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 justify-between">{{ Str::limit($resource->description, 100) }}</p>
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ $route_show }}" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700">Lihat</a>
                        <button 
                            class="delete-material-btn px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700"
                            data-actionUrl="{{ $route_destroy }}"
                            data-col_01="{{ $name }}"
                            >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="w-full text-center text-gray-500 dark:text-gray-300">Tidak ada materi atau tugas tersedia.</div>
    @endforelse
    </div>
    <div class="mt-4 ml-4 mr-4 transition-all"
        x-transition:enter="transition ease-out duration-1000 transform"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-1000 transform"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4">
        {{ $resources->links() }}
    </div>
</div>
