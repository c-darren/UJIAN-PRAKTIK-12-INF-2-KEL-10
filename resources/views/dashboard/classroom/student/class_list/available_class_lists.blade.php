@section('title', $page_title)

<div x-data="classListComponent()" @keydown.escape.window="openCreateModal = false; closeEditModal()" class="relative">

    <!-- Set masterClassId sebagai variabel global JS -->
    <script>
        window.masterClassId = "{{ $masterClass_id }}";
    </script>

    <!-- Grid Kartu -->
    <div class="mt-2 grid grid-cols-[repeat(auto-fill,_minmax(280px,_1fr))] gap-4 mr-4 ml-4 sm:mt-3 lg:mt-3 xl:mt-6">
        @forelse($records as $classList)
            <div class="max-w-md bg-white ml-3 mb-2 dark:bg-gray-800 rounded-lg border border-gray-200 shadow-lg dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-all duration-500 flex flex-col">
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex flex-wrap justify-between items-start mb-4">
                        <h5 class="text-2xl font-bold text-gray-900 dark:text-white flex-1">{{ $classList->class_name }}</h5>
                    </div>
                    <span class="inline-block bg-blue-500 text-white text-lg font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                        {{ $classList->subject->subject_name }}
                    </span>
                    <span class="inline-block text-white text-lg font-semibold py-1.5 px-4 rounded-full 
                        {{ $classList->enrollment_status === 'Open' ? 'bg-green-500 dark:bg-green-600' : 'bg-red-500 dark:bg-red-600' }}">
                        {{ $classList->enrollment_status }}
                    </span>
                </div>

                <div class="mb-2.5 ml-1.5 mr-1.5 flex justify-center items-center bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-white rounded-full p-3 transition duration-500">
                    @if($classList->students->contains(Auth::id()))
                        <a href="{{ route('student.classroom.resources.index', ['masterClass_id' => $masterClass_id, 'class_id' => $classList->id]) }}"
                           class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-600 font-medium rounded-lg text-sm px-4 py-2 transition duration-200 w-full text-center">
                            Kunjungi Kelas
                        </a>
                    @elseif($classList->enrollment_status === 'Open')
                        <button 
                            @click="openJoinModal({
                                id: '{{ $classList->id }}',
                                class_name: '{{ addslashes($classList->class_name) }}',
                            })"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-600 font-medium rounded-lg text-sm px-4 py-2 transition duration-200 w-full">
                            Join Class
                        </button>
                    @else
                        <button 
                            disabled
                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-600 font-medium rounded-lg text-sm px-4 py-2 transition duration-200 w-full cursor-not-allowed">
                            Tidak Menerima Peserta Didik
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                <p class="text-center text-gray-500 dark:text-gray-300">No class lists available.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 ml-4 mr-4">
        {{ $records->links() }}
    </div>

    {{-- Include Confirm Join Modal --}}
    @include('dashboard.classroom.student.class_list.student_join')

</div>

@section('required_scripts')
    <script src="{{ asset('js/classroom/student/class_list/alpine_init.js') }}"></script>
    <script src="{{ asset('js/classroom/student/class_list/student_join.js') }}" defer></script>
@endsection
