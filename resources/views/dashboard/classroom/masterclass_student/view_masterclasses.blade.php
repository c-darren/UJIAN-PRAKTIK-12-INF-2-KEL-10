@section('title', $page_title)

<div class="flex flex-col ml-4 my-4 mb-1 space-y-4">
    @foreach ($records as $record)
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center ml-1 mr-2 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            
            {{-- Class Details --}}
            <div class="flex-1">
                {{-- Class Detail Link --}}
                <a href="{{ route('master-class.detail-class', $record->master_class_id) }}">
                    <h5 class="mb-1 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $record->master_class_name }}
                    </h5>
                </a>

                {{-- Academic Year --}}
                <p class="text-sm font-normal text-gray-700 dark:text-gray-400">
                    Academic Year: {{ $record->academic_year }}
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-4 sm:mt-0 sm:ml-4 flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                @switch($segment)
                    @case('exited-class')
                        @if ($record->master_class_status === 'Archived' || $record->academic_year_status === 'Inactive')
                            <button type="button" 
                                disabled
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-400 rounded-lg cursor-not-allowed focus:ring-4 focus:outline-none focus:ring-green-400 dark:bg-green-600">
                                Cannot Rejoin {{ $record->master_class_name }} (Archived)
                                {{-- <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg> --}}
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                  </svg>                                  
                            </button>
                        @else
                            <button type="button" 
                                data-id="{{ $record->master_class_id }}" 
                                @click="submitExitConfirmation($event)" 
                                class="rejoin-data-btn inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-lg hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-400 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-700">
                                Rejoin Class {{ $record->master_class_name }}
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </button>
                        @endif
                        @break

                    @case('archived-class')
                        <a href="{{ route('master-class.detail-class', $record->master_class_id) }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Go to {{ $record->master_class_name }}
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </a>
                        @break

                    @default
                        <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                            <a href="{{ route('master-class.detail-class', $record->master_class_id) }}" 
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Go to {{ $record->master_class_name }}
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>

                            <button type="button" 
                                data-id="{{ $record->master_class_id }}" 
                                @click="submitExitConfirmation($event)" 
                                class="exit-data-btn inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-700">
                                Exit Class {{ $record->master_class_name }}
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </button>
                        </div>
                @endswitch
            </div>
        </div>
    @endforeach
</div>