@extends('dashboard.components.layout')
@section('title', $page_title)
@section('content')
<div x-data="{
    $store: { 
        createModal: { open: false },
        editModal: { open: false },
        deleteModal: { open: false }
    },
    open: false, 
    data: {}, 
    showModal(tableData) { 
        this.data = tableData; 
        this.open = true; 
    } 
    }"
    @keydown.escape.window="open = false">
    <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 rounded-lg shadow dark:border-gray-700">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Manajemen Topik</h2>
        <button 
            @click="$dispatch('open-create-modal')" 
            class="create-modal-btn mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
            Tambah Topik
        </button>
        <div x-data="{ search: '' }">
            <!-- Input Pencarian -->
            <div>
                <input x-model="search" autofocus type="text" placeholder="Cari Topik..." class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Daftar Topik di dalam div -->
            <div class="mt-2 grid grid-cols-[repeat(auto-fill,_minmax(215px,_1fr))] gap-4 mr-4 ml-4 sm:mt-3 lg:mt-3 xl:mt-6 max-h-[70vh] overflow-y-auto scrollbar-style-1">
                <!-- Input tersembunyi di dalam div untuk sinkronisasi -->
                <input type="hidden" x-ref="search" x-model="search" placeholder="Cari topik..." class="mb-4 px-4 py-2 border rounded-md">
                
                @forelse($topics as $topic)
                    <div class="max-w-md bg-white m-3 mb-2 dark:bg-gray-800 rounded-lg border border-gray-200 shadow-lg dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-all duration-500 flex flex-col"
                        x-show="'{{ strtolower(addslashes($topic->topic_name)) }}'.includes(search.toLowerCase())">
                        <a href="#"> 
                            <div class="pl-6 pr-6 pb-2 pt-2.5 flex flex-col flex-grow">
                                <div class="flex flex-wrap justify-between items-start">
                                    <h5 class="text-lg font-bold text-gray-900 dark:text-white flex-1">{{ $topic->topic_name }}</h5>
                                </div>
                                <span class="inline-block bg-blue-500 text-white text-xs font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                                    Dibuat: {{ $topic->created_at->diffForHumans() }}
                                </span>
                                <span class="inline-block text-white text-xs font-semibold py-1.5 px-4 rounded-full 
                                bg-green-500 dark:bg-green-600">
                                    @if($topic->updated_at)
                                    Terakhir diperbarui: {{ $topic->updated_at->diffForHumans() ?? 'Belum pernah diperbarui' }}
                                    @else
                                    Belum pernah diperbarui
                                    @endif
                                </span>
                            </div>
                        </a>

                        <div class="mb-2.5 ml-1.5 mr-1.5 flex justify-between items-center bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-white rounded-full py-1 px-2 transition duration-500">
                            <button
                                data-actionUrl="{{ route('classroom.topic.update', [$masterClass_id, $classList->id, $topic->id]) }}"  
                                data-col_01="{{ $topic->topic_name }}"
                                class="edit-data-btn text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-600 font-medium rounded-lg text-xs px-4 py-2 transition duration-200">
                                Edit
                            </button>
                            <button 
                                data-actionUrl="{{ route('classroom.topic.destroy', [$masterClass_id, $classList->id, $topic->id]) }}" 
                                data-col_01="{{ $topic->topic_name }}"
                                class="delete-data-btn text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-600 font-medium rounded-lg text-xs px-4 py-2 transition duration-200">
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <p class="text-center text-gray-500 dark:text-gray-300">Tidak ada topik tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Include Partial Views untuk Modals -->
        @include('dashboard.classroom.class_list.topic.create')
        @include('dashboard.classroom.class_list.topic.edit')
        @include('dashboard.classroom.class_list.topic.delete')
    </div>
</div>
@endsection

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/class_list/topic/create.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/class_list/topic/update.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/classroom/class_list/topic/delete.js') }}"></script>
@endsection