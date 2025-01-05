<div wire:poll.5s="loadSubmissions"
     x-data="{ 
        openSections: @entangle('openSections'),
        selectedSubmissions: @entangle('selectedSubmissions'),
        init() {
            window.addEventListener('submission-list-updated', (event) => {
                // Restore checkbox states
                event.detail.selectedSubmissions.forEach(id => {
                    const checkbox = document.querySelector(`input[value='${id}']`);
                    if (checkbox) checkbox.checked = true;
                });
            });
        }
     }"
     class="w-[30%] border-r border-gray-200 dark:border-gray-700 overflow-y-auto scrollbar-style-1">

    <!-- Submitted Section -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 mr-2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Pilih Semua Yang Mengumpulkan</span>
                </label>
            </div>
            <button id="bulkReturnBtn"
                class="w-full text-white bg-primary-700 disabled:bg-gray-400 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:disabled:bg-slate-500 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Kembalikan Terpilih
            </button>
        </div>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div @click="$wire.toggleSection('submitted')" 
             class="p-4 bg-gray-50 dark:bg-gray-800 cursor-pointer flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                Telah Mengumpulkan ({{ $submissions->where('return_status', '!=', 'returned')->count() }})
            </h3>
            <svg :class="{'rotate-180': !openSections.submitted}" 
                 class="w-5 h-5 transition-transform dark:text-white"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div x-show="openSections.submitted" x-collapse>
            @foreach($submissions->where('return_status', '!=', 'returned') as $submission)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer relative" 
                     data-submission-id="{{ $submission->id }}"
                     @click="showSubmission({{ $submission->id }})">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($submission->return_status !== 'returned')
                                <input type="checkbox" name="selected_submissions[]" 
                                       value="{{ $submission->id }}" 
                                       class="submission-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                       @click.stop>
                            @else
                                <div class="w-4"></div>
                            @endif
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->student_name }}</h4>
                                <div class="flex items-center mt-1">
                                    <input type="number" 
                                           name="submissions[{{ $submission->id }}][score]" 
                                           value="{{ $submission->score }}" 
                                           min="0" max="100" 
                                           step="0.01"
                                           class="w-16 text-sm p-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white {{ $submission->return_status === 'returned' ? 'bg-gray-100 dark:bg-gray-600' : '' }}"
                                           @click.stop
                                           {{ $submission->return_status === 'returned' ? 'disabled' : '' }}
                                           @blur="$event.target.disabled ? null : saveDraft({{ $submission->id }}, $event.target.value)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="dropdown-container relative" @click.stop>
                            <button @click="toggleDropdown($event, {{ $submission->id }})" 
                                    type="button"
                                    class="text-gray-500 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>
                            </button>
                            
                            <div x-show="openDropdown === {{ $submission->id }}" 
                                 @click.away="closeDropdowns()"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50">
                                <div class="py-1">
                                    <button @click="returnSingleSubmission({{ $submission->id }}, 'now')"
                                            class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                                        Kembalikan Sekarang
                                    </button>
                                    <button @click="scheduleReturn({{ $submission->id }})"
                                            class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                                        Jadwalkan Pengembalian
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        @if($submission->return_status === 'returned')
                            Dikembalikan: {{ \Carbon\Carbon::parse($submission->returned_at)->format('d/m/Y H:i') }}
                        @elseif($submission->return_status === 'scheduled')
                            Dijadwalkan: {{ \Carbon\Carbon::parse($submission->scheduled_return_at)->format('d/m/Y H:i') }}
                        @elseif($submission->return_status === 'draft')
                            Draft
                        @elseif($submission->return_status === 'submitted')
                            Dikumpulkan: {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') }}
                        @elseif($submission->return_status === 'late')
                            Terlambat
                        @elseif($submission->return_status === 'progress')
                            Sedang Dikerjakan
                        @elseif($submission->return_status === 'assigned')
                            Ditugaskan
                        @elseif($submission->return_status === 'mark as done')
                            Ditandai Selesai
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Returned Section -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div @click="$wire.toggleSection('returned')" 
             class="p-4 bg-gray-50 dark:bg-gray-800 cursor-pointer flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                Sudah Dikembalikan ({{ $submissions->where('return_status', 'returned')->count() }})
            </h3>
            <svg :class="{'rotate-180': !openSections.returned}" class="w-5 h-5 transition-transform dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div x-show="openSections.returned" x-collapse>
            @foreach($submissions->where('return_status', 'returned') as $submission)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer relative bg-green-50 dark:bg-green-900/10" 
                     data-submission-id="{{ $submission->id }}"
                     @click="showSubmission({{ $submission->id }})">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($submission->return_status !== 'returned')
                                <input type="checkbox" name="selected_submissions[]" 
                                       value="{{ $submission->id }}" 
                                       class="submission-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                       @click.stop>
                            @else
                                <div class="w-4"></div>
                            @endif
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->student_name }}</h4>
                                <div class="flex items-center mt-1">
                                    <input type="number" 
                                           name="submissions[{{ $submission->id }}][score]" 
                                           value="{{ $submission->score }}"
                                           inputmode="numeric"
                                           min="0" max="100" 
                                           step="0.01"
                                           class="w-16 text-sm p-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white {{ $submission->return_status === 'returned' ? 'bg-gray-100 dark:bg-gray-600' : '' }}"
                                           @click.stop
                                           {{ $submission->return_status === 'returned' ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        @if($submission->return_status === 'returned')
                            Dikembalikan: {{ \Carbon\Carbon::parse($submission->returned_at)->format('d/m/Y H:i') }}
                        @elseif($submission->return_status === 'scheduled')
                            Dijadwalkan: {{ \Carbon\Carbon::parse($submission->scheduled_return_at)->format('d/m/Y H:i') }}
                        @else
                            Status: Draft
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Not Submitted Section -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div @click="$wire.toggleSection('notSubmitted')" 
             class="p-4 bg-gray-50 dark:bg-gray-800 cursor-pointer flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Belum Mengumpulkan ({{ $nonSubmittingStudents->count() }})</h3>
            <svg :class="{'rotate-180': !openSections.notSubmitted}" class="w-5 h-5 transition-transform dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div x-show="openSections.notSubmitted" x-collapse>
            @foreach($nonSubmittingStudents as $student)
            <div class="p-4 bg-gray-50/50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $student->name }}</h4>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Belum mengumpulkan</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Loading indicator -->
    <div wire:loading.flex class="fixed top-4 right-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
    </div>
</div>
