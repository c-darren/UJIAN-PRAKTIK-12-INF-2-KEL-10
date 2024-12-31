@section('page_content')
@php
    // Menyiapkan data formData dengan kondisi resource_type
    $formData = [
        'topic_id' => $topic_id ?? '',
        'resource_name' => $resource_name ?? '',
        'start_date' => $start_date ?? '',
        'description' => $description ?? '',
        'new_attachments' => [],
        'deleted_attachments' => [], // Untuk melacak lampiran yang dihapus
    ];

    if($resource_type === 'assignment') {
        $formData['end_date'] = $end_date ?? '';
        $formData['accept_late_submissions'] = $accept_late_submissions ?? false;
    }

    // Siapkan lampiran yang sudah ada
    $existingAttachments = [];
    if (!empty($attachments) && !empty($attachment_file_names)) {
        foreach ($attachments as $idx => $path) {
            // Ambil nama file, kalau tidak ada di $attachment_file_names maka fallback ke basename
            $fileName = $attachment_file_names[$idx] ?? basename($path);

            // Deteksi extension (untuk keperluan preview)
            $fileType = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            // Bangun URL preview (opsional, jika Anda memiliki route preview)
            // Contoh route: classroom.resources.view-attachment
            $attachmentUrl = route('student.classroom.resources.view-attachment', [
                'masterClass_id'   => $masterClass_id,
                'class_id'         => $classList->id,
                'type'             => $resource_type,
                'resource_id'      => $resource_id,
                'attachment_index' => $idx,
            ]);

            $downloadUrl = route('student.classroom.resources.download-attachment', [
                'masterClass_id'   => $masterClass_id,
                'class_id'         => $classList->id,
                'type'             => $resource_type,
                'resource_id'      => $resource_id,
                'attachment_index' => $idx,
            ]);

            $existingAttachments[] = [
                'index'        => $idx,         // index integer
                'fileName'     => $fileName,    // nama file "asli"
                'fileType'     => $fileType,    // 'pdf' / 'png' / 'docx' / dsb
                'attachmentUrl'=> $attachmentUrl,// untuk modal preview
                'downloadUrl'  => $downloadUrl, // untuk tombol download
                'path'         => $path,        // path di storage
            ];
        }
    }
@endphp

<div 
    x-data='{
        formData: @json($formData),
        {{-- actionUrl: "{{ route('classroom.resources.update', [$masterClass_id, $classList->id, $resource_type, $resource_id]) }}" --}}
        showDetailModal: false,
    }'
    class="p-4 bg-white dark:bg-gray-800 border border-gray-200 shadow dark:border-gray-700">

    {{-- Indikator Tipe (Tugas atau Materi) --}}
    <span class="inline-block py-1 px-2 rounded bg-indigo-200 text-gray-900 text-xs font-medium tracking-widest">
        @if($resource_type == 'assignment')
            Tugas
        @elseif($resource_type == 'material')
            Materi
        @endif
    </span>
    

    @if($resource_type == 'assignment')
        @if($parsed_end_date->isPast())
            <span class="inline-block py-1 px-2 rounded bg-red-200 text-red-500 text-xs font-semibold tracking-widest">
                DEADLINE TERLEWATI
            </span>
            <span class="inline-block py-1 px-2 rounded bg-red-200 text-red-500 text-xs font-semibold tracking-widest">
                {{ $formatted_end_date }}
            </span>
        @else
            <span class="inline-block py-1 px-2 rounded bg-green-200 text-green-500 text-xs font-semibold tracking-widest">
                {{ $formatted_end_date }}
            </span>
        @endif
    @endif

    <div class="py-1 flex flex-wrap md:flex-nowrap">
        <!-- BAGIAN KIRI (Judul, Deskripsi, Attachments) -->
        <div class="md:flex-grow lg:w-3/4 w-full">
            <!-- Header dengan Resource Name dan Icon -->
            <div class="flex items-center space-x-2">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $resource_name }}</h2>
                <button @click="showDetailModal = true" 
                        class="inline-flex items-center text-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Deskripsi -->
            <div class="relative overflow-x-auto shadow-xl sm:rounded-lg lg:max-h-[70vh] max-h-[60vh] overflow-y-auto p-2.5 rounded-xl">
                <p class="dark:text-white leading-relaxed mb-2 text-sm text-justify py-1">
                    {{ $description }}
                </p>
            </div>

            <!-- Menampilkan Attachments -->
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Attachments
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($existingAttachments as $attachment)
                        <div class="relative mb-1.5 mr-1 inline-flex items-center border-2 border-collapse 
                                   rounded-xl px-2 py-1 max-w-[200px] min-h-[40px] max-h-[60px] justify-center 
                                   bg-gray-50 dark:bg-gray-700 cursor-pointer">
                            <a href="#" 
                               class="w-full h-full flex items-center justify-center file-preview-link"
                               data-fileUrl="{{ $attachment['attachmentUrl'] }}"
                               data-downloadUrl="{{ $attachment['downloadUrl'] }}"
                               data-fileType="{{ $attachment['fileType'] }}" 
                               data-title="{{ Str::length($attachment['fileName']) > 90 ? Str::limit($attachment['fileName'], 90) : Str::words($attachment['fileName'], 18) }}">
                                <span class="title-font font-medium text-gray-900 dark:text-white text-xs truncate"
                                      title="{{ $attachment['fileName'] }}">
                                    {{ $attachment['fileName'] }}
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN (Pengumpulan) -->
        @php
            $now = \Carbon\Carbon::now();
            $endDate = isset($parsed_end_date) ? $parsed_end_date : null;
            $isExpired = $endDate ? $now->isAfter($endDate) : false;
            $canSubmit = !$isExpired || $accept_late_submissions;

        @endphp
        <div class="md:flex-grow lg:w-1/4 w-full lg:ml-2">
            <div x-data="submissionManager">

                <div class="p-4 dark:bg-gray-700 bg-gray-50 rounded-2xl">
                    @if($resource_type === 'assignment')
                        @if(!$submission || $submission->attachment === [])
                            <!-- Show both buttons if no submission or no attachments -->
                            <div class="space-y-2">
                                @if($canSubmit)
                                    <button @click="showAddSubmissionModal = true" 
                                            class="w-full text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all">
                                        + Tambahkan tugas
                                    </button>

                                    <button @click="showMarkCompleteModal = true"
                                            class="w-full dark:text-white text-yellow-700 hover:text-white border border-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">
                                        Tandai sebagai selesai
                                    </button>
                                @endif
                            </div>

                        @elseif(in_array($submission->return_status, ['submitted', 'late', 'mark as done']))
                            <!-- Sudah mengumpulkan -->
                            @if($canSubmit)
                                <div class="space-y-2">
                                    <button @click="cancelSubmission({{ $submission->id }})"
                                        class="w-full dark:text-white text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">
                                        Batalkan Pengumpulan
                                    </button>
                                </div>
                            @else
                                <div class="text-center p-4 bg-red-100 dark:bg-red-800 rounded-lg">
                                    <p class="text-red-600 dark:text-red-200">
                                        Tidak menerima penarikan tugas
                                    </p>
                                </div>
                            @endif

                        @elseif(in_array($submission->return_status, ['scheduled', 'draft']))
                            <div class="text-center p-4 bg-yellow-100 dark:bg-yellow-800 rounded-lg">
                                <p class="text-yellow-600 dark:text-yellow-200">
                                    Tugas akan segera dikembalikan
                                </p>
                            </div>

                        @elseif($submission->return_status === 'progress')
                            @if($canSubmit)
                                <button @click="showAddSubmissionModal = true" 
                                        class="w-full text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all">
                                    + Tambahkan tugas
                                </button>
                            @else
                                <div class="text-center p-4 bg-red-100 dark:bg-red-800 rounded-lg">
                                    <p class="text-red-600 dark:text-red-200">
                                        Tidak menerima pengumpulan tugas
                                    </p>
                                </div>
                            @endif
                        @endif
                        @if($submission->return_status === 'returned')
                            <div class="text-center p-4 bg-green-100 dark:bg-cyan-600 rounded-lg">
                                <p class="text-green-600 font-bold dark:text-white">
                                    Nilai: {{ $submission->score ?? '-' }}
                                </p>
                            </div>
                            @if($canSubmit)
                                <button @click="showAddSubmissionModal = true" 
                                        class="mt-4 w-full text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all">
                                    + Tambahkan tugas
                                </button>
                            @else
                                <div class="mt-4 text-center p-4 bg-red-100 dark:bg-red-800 rounded-lg">
                                    <p class="text-red-600 dark:text-red-200">
                                        Tidak menerima pengumpulan tugas
                                    </p>
                                </div>
                            @endif
                        @endif

                        <!-- Tampilkan Attachments -->
                        @if($submission && count($studentAttachments) > 0)
                            <div class="mt-4">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">File yang Dikumpulkan:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($studentAttachments as $attachment)
                                    <div
                                        x-data="{ isDeleted: false }"
                                        :class="isDeleted ? 'opacity-50 line-through' : 'opacity-100'"
                                        class="relative mb-1.5 mr-1 inline-flex items-center border-2 border-collapse 
                                            rounded-xl px-2 py-1 max-w-[200px] min-h-[40px] max-h-[60px] justify-center 
                                            bg-gray-50 dark:bg-gray-700 cursor-pointer transition-opacity duration-200">
                                        
                                        <a 
                                            href="#" 
                                            :class="deleteAttachmentsList.includes(@js($attachment['path'])) ? 'opacity-50 line-through' : 'opacity-100'"
                                            class="w-full h-full flex items-center justify-center file-preview-link"
                                            data-fileUrl="{{ $attachment['attachmentUrl'] }}"
                                            data-downloadUrl="{{ $attachment['downloadUrl'] }}"
                                            data-fileType="{{ $attachment['fileType'] }}" 
                                            data-title="{{ Str::length($attachment['fileName']) > 90 ? Str::limit($attachment['fileName'], 90) : Str::words($attachment['fileName'], 18) }}">
                                            
                                            <!-- Nama Lampiran -->
                                            <span 
                                                class="title-font font-medium text-gray-900 dark:text-white text-xs truncate"
                                                title="{{ $attachment['fileName'] }}">
                                                {{ $attachment['fileName'] }}
                                            </span>
                                            
                                        </a>
                                        @if($submission->return_status === 'progress')
                                            <button
                                                type="button"
                                                class="absolute top-0 right-0 mt-1 mr-1 text-red-700 hover:text-red-900 
                                                    w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-700 rounded-full text-lg font-bold"
                                                @click.prevent="toggleDeleteAttachment(@js($attachment['path']), @js($attachment['fileName']))">
                                                <span x-text="deleteAttachmentsList.includes(@js($attachment['path'])) ? '↺' : '×'"></span>
                                            </button>
                                        <!-- Hidden input untuk mengirim data ke server -->
                                        <template x-if="isDeleted">
                                            <input type="hidden" name="delete_attachments[]" value="{{ $attachment['path'] }}">
                                        </template>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                @include('dashboard.classroom.student.resource.partials.add_submission')
                @include('dashboard.classroom.student.resource.partials.mark_complete')
            </div>        
        </div>
    </div>

    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="py-1 md:col-span-3">
            <hr class="mt-2 mb-0.5">
        </div>
        <div class="py-1 md:col-span-2">
            <span class="font-semibold title-font dark:text-white text-gray-700">Komentar Kelas
            </span>
            @if($resource_type == 'material')
                @livewire('classroom.teacher-comment-material-table', [
                    'masterClass_id' => $masterClass_id, 
                    'classList_id' => $classList->id, 
                    'resource_id' => $resource_id
                ])
            @elseif($resource_type == 'assignment')
                @livewire('classroom.teacher-comment-assignment-table', [
                    'masterClass_id' => $masterClass_id, 
                    'classList_id' => $classList->id, 
                    'resource_id' => $resource_id
                ])
            @endif
        </div>
        <div x-data="feedbackManager" class="max-h-[70vh] overflow-y-auto scrollbar-style-1 my-3 dark:bg-gray-700 bg-gray-50 rounded-2xl">
            <!-- Feedback Section -->
            <div class="mt-4 px-4 py-2 border-t border-gray-200 dark:border-gray-700 scrollbar-style-1">
                <div class="flex justify-between items-center">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Komentar Pribadi</h4>
                    <button @click="addFeedback({{ $submission->id }})" 
                            class="text-sm text-primary-600 hover:underline">
                        + Tambah Komentar Pribadi                     
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
                    placeholder="Tulis komentar pribadi..."
                ></textarea>
                <div class="flex justify-end mt-2 space-x-2">
                    <button @click="saveFeedback({{ $submission->id }})"
                            class="px-3 py-1 text-sm text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                        Simpan
                    </button>
                    <button @click="cancelFeedback()"
                            class="px-3 py-1 text-sm text-white dark:bg-red-600 bg-red-500 rounded-lg cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>
        </div>

    </div>
    @include('dashboard.classroom.student.resource.partials.view_attachment_modal')
    {{-- Modal Detail --}}
    @include('dashboard.classroom.student.resource.partials.modal_detail')
</div>
@endsection

@section('required_scripts')
<script>
    // Define required variables first
    const masterClass_id = '{{ $masterClass_id }}';
    const classList_id = '{{ $classList->id }}';
    const assignment_id = '{{ $resource_id }}';
    const submission_id = '{{ $submission_id ?? "" }}';
</script>
<script type="text/javascript" src="{{ asset('js/classroom/student/resource/modal_file.js') }}"></script>
@if($resource_type == 'assignment')
    <script type="text/javascript" src="{{ asset('js/classroom/student/resource/submission_modal.js') }}"></script>
@endif
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('comment-posted', () => {
        });
    });
    document.addEventListener('alpine:init', () => {
        Alpine.data('resourceDetail', () => ({
            async addFeedback(submissionId) {
                this.showFeedbackInput = true;
            },
        }));

        Alpine.data('feedbackManager', () => ({
            showFeedbackInput: false,
        
            async addFeedback(submissionId) {
                this.showFeedbackInput = true;
            },
        
            async saveFeedback(submissionId) {
                try {
                    const content = document.getElementById(`new-feedback-${submissionId}`).value;
                    if (!content.trim()) return;
                    
                    const response = await axios.post("{{ route('student.classroom.resources.store-feedback', [$masterClass_id, $classList->id, $submission->id]) }}", {
                        content: content
                    });
                    
                    if (response.data.success) {
                        window.location.reload();
                        this.showFeedbackInput = false;
                        Notiflix.Notify.success('Feedback berhasil disimpan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Notiflix.Notify.failure('Gagal menyimpan feedback');
                }
            },
            
            cancelFeedback() {
                this.showFeedbackInput = false;
            },
        }));
    });
</script>
@endsection