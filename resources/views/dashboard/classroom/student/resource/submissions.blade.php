<div x-data="submissionManager" class="flex h-[calc(100vh-200px)]">
    <!-- Bagian Kiri - Daftar Peserta (30%) -->
    <div x-data="{ 
        openSections: {
            submitted: true,
            returned: false,
            notSubmitted: false
        }
    }" class="w-[30%] border-r border-gray-200 dark:border-gray-700 overflow-y-auto scrollbar-style-1">
        <!-- Submitted Assignments Section -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 mr-2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Pilih Semua Yang Mengumpulkan</span>
                    </label>
                </div>
                <button id="bulkReturnBtn" disabled
                    class="w-full text-white bg-primary-700 disabled:bg-gray-400 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:disabled:bg-slate-500 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Kembalikan Terpilih
                </button>
            </div>
        </div>

        <!-- Submitted Not Returned -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div @click="openSections.submitted = !openSections.submitted" 
                 class="p-4 bg-gray-50 dark:bg-gray-800 cursor-pointer flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Telah Mengumpulkan ({{ $submissions->where('return_status', '!=', 'returned')->count() }})
                </h3>
                <svg :class="{'rotate-180': !openSections.submitted}" class="w-5 h-5 transition-transform dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            
                            <!-- Bagian dropdown button -->
                            @if($submission->return_status !== 'returned')
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
                                        @if(!session()->has("return_confirmed_{$submission->id}"))
                                        <button @click="returnSingleSubmission({{ $submission->id }}, 'now')"
                                                class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                                            Kembalikan Sekarang
                                        </button>
                                        @endif
                                        <button @click="scheduleReturn({{ $submission->id }})"
                                                class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                                            Jadwalkan Pengembalian
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                        
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            @if($submission->return_status === 'returned')
                                Dikembalikan: {{ \Carbon\Carbon::parse($submission->returned_at)->format('d/m/Y H:i') }}
                            @elseif($submission->return_status === 'scheduled')
                                Dijadwalkan: {{ \Carbon\Carbon::parse($submission->scheduled_return_at)->format('d/m/Y H:i') }}
                            @else
                                Draft
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Returned Submissions -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div @click="openSections.returned = !openSections.returned" 
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
                            
                            <!-- Bagian dropdown button -->
@if($submission->return_status !== 'returned')
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
            @if(!session()->has("return_confirmed_{$submission->id}"))
            <button @click="returnSingleSubmission({{ $submission->id }}, 'now')"
                    class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                Kembalikan Sekarang
            </button>
            @endif
            <button @click="scheduleReturn({{ $submission->id }})"
                    class="w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                Jadwalkan Pengembalian
            </button>
        </div>
    </div>
</div>
@endif

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

        <!-- Non-submitting Students -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div @click="openSections.notSubmitted = !openSections.notSubmitted" 
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
    </div>

    <!-- Bagian Kanan - Preview Tugas (70%) -->
    <div class="w-[70%] bg-gray-50 dark:bg-gray-800">
        <div id="submissionPreview" class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
            Pilih peserta didik untuk melihat tugasnya
        </div>
    </div>
    
    {{-- Include the modal component --}}
    @include('dashboard.classroom.class_list.resource.partials.return-modal')

    @include('dashboard.classroom.class_list.resource.partials.bulk_return-modal')
    @include('dashboard.classroom.class_list.resource.partials.single_return-modal')
    @include('dashboard.classroom.class_list.resource.partials.return_now_confirmation')
</div>

@section('required_scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('submissions', {
        selectedSubmissions: new Set(),
        returnType: 'now',
        scheduledTime: null,
        
        toggleSelection(id) {
            if (this.selectedSubmissions.has(id)) {
                this.selectedSubmissions.delete(id);
            } else {
                this.selectedSubmissions.add(id);
            }
            this.updateBulkReturnButton();
        },

        selectAll(checked) {
            const checkboxes = document.querySelectorAll('.submission-checkbox');
            checkboxes.forEach(checkbox => {
                if (checked) {
                    this.selectedSubmissions.add(parseInt(checkbox.value));
                } else {
                    this.selectedSubmissions.delete(parseInt(checkbox.value));
                }
                checkbox.checked = checked;
            });
            this.updateBulkReturnButton();
        },

        updateBulkReturnButton() {
            const button = document.getElementById('bulkReturnBtn');
            button.disabled = this.selectedSubmissions.size === 0;
        },

        async submitGrade() {
            try {
                const submissionsData = [];
                this.selectedSubmissions.forEach(id => {
                    const scoreInput = document.querySelector(`input[name="submissions[${id}][score]"]`);
                    const feedbackTextarea = document.querySelector(`#feedback-${id}`);
                    submissionsData.push({
                        id: id,
                        score: parseFloat(scoreInput.value),
                        feedback: feedbackTextarea?.value
                    });
                });

                await axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/bulk-grade`, {
                    submissions: submissionsData,
                    return_status: this.returnType,
                    scheduled_return_at: this.returnType === 'scheduled' ? this.scheduledTime : null
                });

                Notiflix.Notify.success('Nilai berhasil disimpan');
                location.reload();
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal menyimpan nilai');
            }
        },

        // Di dalam method confirmReturn
        async confirmReturn() {
            try {
                await this.returnSingleSubmission(this.selectedSubmissionId, 'now');
                
                await axios.post(
                    `/classroom/${masterClass_id}/${classList_id}/resources/submissions/set-return-confirmation`,
                    { submission_id: this.selectedSubmissionId }
                );
                
                this.returnNowModalOpen = false;
                location.reload();
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal mengembalikan tugas');
            }
        }

        async scheduleSubmissionReturn(scheduledTime) {
            const submissionId = this.selectedSubmission;
            try {
                await this.returnSingleSubmission(submissionId, 'scheduled', scheduledTime);
                this.$refs.returnModal.hide();
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal menjadwalkan pengembalian');
            }
        }
    });

    Alpine.data('submissionManager', () => ({
        openDropdown: null,
        showFeedbackInput: false,
        returnNowModalOpen: false,
        scheduleModalOpen: false,
        selectedSubmissionId: null,
        scheduledTime: null,

        async showSubmission(submissionId) {
            try {
                const preview = document.getElementById('submissionPreview');
                preview.classList.add('animate-pulse');

                const response = await axios.get(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/preview`);
                preview.innerHTML = response.data.html;
                preview.classList.remove('animate-pulse');

                document.querySelectorAll('[data-submission-id]').forEach(el => {
                    el.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                });
                document.querySelector(`[data-submission-id="${submissionId}"]`).classList.add('bg-gray-100', 'dark:bg-gray-700');
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal memuat tugas');
            }
        },

        async saveDraft(submissionId, score) {
            try {
                const response = await axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/grade`, {
                    score: parseFloat(score)
                });
                
                if (response.status === 200) {
                    Notiflix.Notify.success('Nilai tersimpan');
                }
            } catch (error) {
                console.error('Error:', error);
                const errorMessage = error.response?.data?.message || 'Gagal menyimpan nilai';
                Notiflix.Notify.failure(errorMessage);
            }
        },

        async loadSubmissionPreview(submissionId) {
            try {
                const response = await axios.get(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/preview`);
                
                if (response.status === 200 && response.data.html) {
                    const preview = document.getElementById('preview');
                    preview.innerHTML = response.data.html;
                    preview.classList.remove('animate-pulse');

                    document.querySelectorAll('[data-submission-id]').forEach(el => {
                        el.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                    });
                    
                    const selectedSubmission = document.querySelector(`[data-submission-id="${submissionId}"]`);
                    if (selectedSubmission) {
                        selectedSubmission.classList.add('bg-gray-100', 'dark:bg-gray-700');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const errorMessage = error.response?.data?.message || 'Gagal memuat tugas';
                Notiflix.Notify.failure(errorMessage);
            }
        },

        toggleDropdown(event, id) {
            event.stopPropagation();
            this.openDropdown = this.openDropdown === id ? null : id;
        },

        closeDropdowns() {
            this.openDropdown = null;
        },

        async addFeedback(submissionId) {
            this.showFeedbackInput = true;
        },
        
        async saveFeedback(submissionId) {
            try {
                const content = document.getElementById(`new-feedback-${submissionId}`).value;
                if (!content.trim()) return;
                
                const response = await axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/feedback`, {
                    content: content
                });
                
                if (response.data.success) {
                    await this.showSubmission(submissionId);
                    this.showFeedbackInput = false;
                    Notiflix.Notify.success('Feedback berhasil disimpan');
                }
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal menyimpan feedback');
            }
        },
        
        async deleteFeedback(submissionId, index) {
            try {
                const response = await axios.delete(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/feedback/${index}`);
                
                if (response.data.success) {
                    await this.showSubmission(submissionId);
                    Notiflix.Notify.success('Feedback berhasil dihapus');
                }
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Gagal menghapus feedback');
            }
        },
        
        cancelFeedback() {
            this.showFeedbackInput = false;
        },

        showPreview(element) {
            const previewModal = document.getElementById('previewModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');
            const openInNewTabBtn = document.getElementById('openInNewTabBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            
            const fileUrl = element.dataset.fileurl;
            const downloadUrl = element.dataset.downloadurl;
            const fileName = element.dataset.title;
            const fileType = element.dataset.filetype;
            
            modalTitle.textContent = fileName;
            // Bersihkan konten modal sebelum menambahkan yang baru
            modalContent.innerHTML = '';

            if (fileType === 'pdf') {
                // Tampilkan PDF dalam iframe
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl + '#toolbar=0';
                iframe.classList.add('w-full', 'h-[70vh]');
                iframe.setAttribute('frameborder', '0');
                modalContent.appendChild(iframe);
            } else if (fileType === 'png' || fileType === 'jpg' || fileType === 'jpeg' || fileType === 'gif' || fileType === 'webp') {
                // Tampilkan image
                const img = document.createElement('img');
                img.src = fileUrl;
                img.classList.add('max-w-full', 'max-h-[70vh]');
                modalContent.appendChild(img);
            } else {
                // Jika tipe file belum didukung
                const msg = document.createElement('p');
                msg.textContent = 'File type not supported.';
                modalContent.appendChild(msg);
            }
            openInNewTabBtn.onclick = () => window.open(fileUrl, '_blank');
            downloadBtn.onclick = () => window.open(downloadUrl, '_blank');
            
            // Show modal
            previewModal.classList.remove('hidden');
            
            // Close handlers
            closeModalBtn.onclick = () => previewModal.classList.add('hidden');
            previewModal.onclick = (e) => {
                if (e.target === previewModal) {
                    previewModal.classList.add('hidden');
                }
            };
        },

        async returnSingleSubmission(submissionId, type = 'now') {
            if(type === 'now') {
                this.selectedSubmissionId = submissionId;
                this.returnNowModalOpen = true;
                return;
            }
        },

        scheduleReturn(submissionId) {
            this.selectedSubmissionId = submissionId;
            this.scheduleModalOpen = true;
        },

        async confirmReturn() {
            try {
                const score = document.querySelector(`input[name="submissions[${this.selectedSubmissionId}][score]"]`).value;
                
                const response = await axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/bulk-return`, {
                    submissions: [{
                        id: this.selectedSubmissionId,
                        score: score
                    }],
                    return_status: 'now'
                });

                if (response.data.success) {
                    await axios.post(
                        `/classroom/${masterClass_id}/${classList_id}/resources/submissions/set-return-confirmation`,
                        { submission_id: this.selectedSubmissionId }
                    );
                    Notiflix.Notify.success('Tugas berhasil dikembalikan');
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure(error.response?.data?.message || 'Gagal mengembalikan tugas');
            }
            this.returnNowModalOpen = false;
        },

        submitSchedule() {
            if (!this.scheduledTime) {
                Notiflix.Notify.failure('Pilih waktu pengembalian');
                return;
            }

            try {
                const score = document.querySelector(`input[name="submissions[${this.selectedSubmissionId}][score]"]`).value;
                
                axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/bulk-return`, {
                    submissions: [{
                        id: this.selectedSubmissionId,
                        score: score
                    }],
                    return_status: 'scheduled',
                    scheduled_return_at: this.scheduledTime
                }).then(response => {
                    if (response.data.success) {
                        Notiflix.Notify.success('Pengembalian berhasil dijadwalkan');
                        location.reload();
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    Notiflix.Notify.failure(error.response?.data?.message || 'Gagal menjadwalkan pengembalian');
                });

                this.scheduleModalOpen = false;
            } catch (error) {
                console.error('Error:', error);
                Notiflix.Notify.failure('Terjadi kesalahan saat menjadwalkan pengembalian');
            }
        },

        init() {
            // Initialize click handlers for preview links
            document.addEventListener('click', (e) => {
                if (e.target.closest('.file-preview-link')) {
                    e.preventDefault();
                    this.showPreview(e.target.closest('.file-preview-link'));
                }
            });
        }

    }));
});

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox?.addEventListener('change', (e) => {
        Alpine.store('submissions').selectAll(e.target.checked);
    });

    document.querySelectorAll('.submission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            Alpine.store('submissions').toggleSelection(parseInt(checkbox.value));
        });
    });

    // Bulk return button handler
    document.getElementById('bulkReturnBtn').addEventListener('click', () => {
        document.getElementById('bulkReturnModal').classList.remove('hidden');
    });

    // Return type radio handler
    document.querySelectorAll('input[name="returnType"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            const scheduledContainer = document.getElementById('scheduledTimeContainer');
            if (e.target.value === 'scheduled') {
                scheduledContainer.classList.remove('hidden');
            } else {
                scheduledContainer.classList.add('hidden');
            }
        });
    });
});

function closeBulkReturnModal() {
    document.getElementById('bulkReturnModal').classList.add('hidden');
}

async function submitBulkReturn() {
    try {
        const returnType = document.querySelector('input[name="returnType"]:checked').value;
        const scheduledTime = returnType === 'scheduled' ? document.getElementById('scheduledTime').value : null;
        
        const selectedSubmissions = Array.from(Alpine.store('submissions').selectedSubmissions);
        const submissionsData = selectedSubmissions.map(id => ({
            id: id,
            score: document.querySelector(`input[name="submissions[${id}][score]"]`).value
        }));

        await axios.post(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/bulk-return`, {
            submissions: submissionsData,
            return_status: returnType,
            scheduled_return_at: scheduledTime
        });

        Notiflix.Notify.success('Tugas berhasil dikembalikan');
        closeBulkReturnModal();
        location.reload();
    } catch (error) {
        console.error('Error:', error);
        Notiflix.Notify.failure(error.response?.data?.message || 'Gagal mengembalikan tugas');
    }
}
</script>
@endsection