<div class="h-full flex flex-col w-full">
    <!-- Header -->
    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ $submission->student->name }}
        </h3>
        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
            <span class="mr-4">Dikumpulkan: {{ \Carbon\Carbon::parse($submission->created_at)->format('D, j M Y H:i:s') }}</span>
            @if($submission->assignment->end_date < $submission->created_at)
                <span class="text-yellow-500 dark:text-yellow-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                    Terlambat
                </span>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 p-4 max-h-[70vh] overflow-y-auto scrollbar-style-1">
        @if($submission->attachment)
            @php 
                $attachments = json_decode($submission->attachment, true);
                $fileNames = json_decode($submission->attachment_file_name, true);
                $existingAttachments = [];
                
                foreach($attachments as $idx => $path) {
                    $fileName = $fileNames[$idx] ?? basename($path);
                    $fileType = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    
                    $attachmentUrl = route('classroom.resources.view-attachment', [
                        'masterClass_id' => $submission->assignment->classList->master_class_id,
                        'class_id' => $submission->assignment->class_id,
                        'type' => 'submission',
                        'resource_id' => $submission->id,
                        'attachment_index' => $idx,
                    ]);

                    $downloadUrl = route('classroom.resources.download-attachment', [
                        'masterClass_id' => $submission->assignment->classList->master_class_id,
                        'class_id' => $submission->assignment->class_id,
                        'type' => 'submission',
                        'resource_id' => $submission->id,
                        'attachment_index' => $idx,
                    ]);

                    $existingAttachments[] = [
                        'index' => $idx,
                        'fileName' => $fileName,
                        'fileType' => $fileType,
                        'attachmentUrl' => $attachmentUrl,
                        'downloadUrl' => $downloadUrl,
                        'path' => $path
                    ];
                }
            @endphp
            
            <div class="grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                @foreach($existingAttachments as $attachment)
                <a href="javascript:void(0)" 
                   class="file-preview-link flex items-center justify-between"
                   data-fileurl="{{ $attachment['attachmentUrl'] }}"
                   data-downloadurl="{{ $attachment['downloadUrl'] }}"
                   data-filetype="{{ $attachment['fileType'] }}" 
                   data-title="{{ Str::length($attachment['fileName']) > 90 ? Str::limit($attachment['fileName'], 90) : Str::words($attachment['fileName'], 18) }}">
                    
                    <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gray-100 rounded dark:bg-gray-700">
                                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                            </div>
                            <span class="title-font font-medium text-gray-900 dark:text-white text-xs"
                                    title="{{ $attachment['fileName'] }}">
                                {{ Str::length($attachment['fileName']) > 25 ? Str::limit($attachment['fileName'], 25) : Str::words($attachment['fileName'], 5) }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 dark:text-gray-400">
                Tidak ada file yang dilampirkan
            </div>
        @endif
    </div>

    <!-- Feedback Section -->
    <div class="mt-4 px-4 py-2 border-t border-gray-200 dark:border-gray-700 scrollbar-style-1">
        <div class="flex justify-between items-center">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Feedback</h4>
            <button @click="addFeedback({{ $submission->id }})" 
                    class="text-sm text-primary-600 hover:underline">
                + Tambah Feedback
            </button>
        </div>
        
    </div>
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 max-h-[30vh] overflow-y-auto scrollbar-style-1">
        <div id="feedback-container-{{ $submission->id }}" class="space-y-2">
            @if($submission->feedback)
                @foreach(json_decode($submission->feedback, true) ?? [] as $index => $feedback)
                    @php
                        $feedbackUser = \App\Models\Auth\User::find($feedback['user_id']);
                    @endphp
                    <div class="feedback-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div class="text-sm text-gray-900 dark:text-white">
                                @if($feedbackUser->name == Auth::user()->name)
                                    <span class="font-semibold">Anda</span>
                                @else
                                    <span class="font-semibold">{{ $feedbackUser->name }}</span>
                                @endif
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $feedback['timestamp'] ?? '-' }}
                                </div>
                            </div>
                            @if(auth()->id() == $feedback['user_id'])
                                <button @click="deleteFeedback({{ $submission->id }}, {{ $index }})"
                                        class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <div class="text-sm text-gray-900 dark:text-white mt-2">
                            {{ $feedback['content'] }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div x-show="showFeedbackInput" class="mt-4 p-4">
        <textarea 
            id="new-feedback-{{ $submission->id }}"
            class="w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            rows="4"
            placeholder="Tulis feedback baru..."
        ></textarea>
        <div class="flex justify-end mt-2 space-x-2">
            <button @click="saveFeedback({{ $submission->id }})"
                    class="px-3 py-1 text-sm text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                Simpan
            </button>
            <button @click="cancelFeedback()"
                    class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">
                Batal
            </button>
        </div>
    </div>
</div>

{{-- View Attachment Modal --}}
@include('dashboard.classroom.class_list.resource.partials.view_attachment_modal')