@extends('dashboard.components.layout')

@section('title', $page_title)

@section('content')
    <div class="mr-4 ml-4 mt-5">
        @if (isset($showVerificationAlert) && $showVerificationAlert)
        <div id="toast-warning" class="flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-yellow-100 rounded-lg dark:bg-yellow-700 dark:text-orange-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <span class="sr-only">Email Verification Required</span>
            </div>
            <div class="ms-3 text-sm font-normal">
                Your email has not been verified yet. Please verify your email to unlock all features.
                <form id="resend-form" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button onclick="submitResendEmailForm()" id="resend-button" type="button" class="text-white mt-2 ml-1 bg-green-700 hover:bg-green-800 px-3 py-1.5 rounded-full disabled:opacity-50 disabled:cursor-not-allowed">
                        Resend Verification Email
                    </button>
                </form>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
        @else
        <div id="toast-success" class="flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">
                Your email has been verified.
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
    </div>
    <div class="grid grid-cols-1 px-4 xl:grid-cols-2 xl:gap-4">
        <!-- Archived Classes -->
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 xl:mb-0" x-data="{ search: '' }">
            <h2 class="text-lg font-semibold mb-2 text-white">Archived Classes</h2>
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
                            <a href="{{ route('classroom.index', ['masterClass_id' => $class['master_class_id'], 'class_id' => $list['class_list_id']]) }}">
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex flex-wrap justify-between items-start mb-4">
                                        <h5 class="text-xl font-bold text-gray-900 dark:text-white flex-1">{{ $list['class_name'] }}</h5>
                                    </div>
                                    <span class="inline-block bg-blue-500 text-white text-sm font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                                        {{ $list['subject'] }}
                                    </span>
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
            <h2 class="text-lg font-semibold mb-2 text-white">Active Classes</h2>
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
                            <a href="{{ route('classroom.index', ['masterClass_id' => $class['master_class_id'], 'class_id' => $list['class_list_id']]) }}">
                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex flex-wrap justify-between items-start mb-4">
                                        <h5 class="text-xl font-bold text-gray-900 dark:text-white flex-1">{{ $list['class_name'] }}</h5>
                                    </div>
                                    <span class="inline-block bg-blue-500 text-white text-sm font-semibold py-1.5 px-4 rounded-full dark:bg-blue-700 mt-2 mb-2.5">
                                        {{ $list['subject'] }}
                                    </span>
                                    <span class="inline-block text-white text-sm font-semibold py-1.5 px-4 rounded-full 
                                        {{ $list['enrollment_status'] === 'Open' ? 'bg-green-500 dark:bg-green-600' : 'bg-red-500 dark:bg-red-600' }}">
                                        {{ $list['enrollment_status'] }}
                                    </span>
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
@endsection

@section('required_scripts')
    @if (isset($showVerificationAlert) && $showVerificationAlert)
        <script src="{{ asset('js/authentication/email/verification.js') }}"></script>
    @endif
@endsection