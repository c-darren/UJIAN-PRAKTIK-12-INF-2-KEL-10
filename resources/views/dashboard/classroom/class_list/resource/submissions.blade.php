<div x-data="submissionManager()" 
     x-init="init()"
     class="flex h-[calc(100vh-200px)]">
    <!-- Bagian Kiri - Daftar Peserta Didik (30%) -->
        <livewire:classroom.submission-list-component 
            :master-class-id="$masterClass_id"
            :class-list-id="$classList_id"
            :resource-id="$resource_id" />

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

        init() {
            this.$watch('$wire.selectedSubmissions', value => {
                this.updateBulkReturnButton();
            });
            Livewire.on('submissionUpdated', () => {
                this.$wire.emit('refreshSubmissions');
            });
        },

        async showSubmission(submissionId) {
            try {
                const response = await axios.get(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/preview`);
                document.getElementById('submissionPreview').innerHTML = response.data.html;
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
            } else if (type === 'scheduled') {
                try {
                    const score = document.querySelector(`input[name="submissions[${this.selectedSubmissionId}][score]"]`).value;
                    
                    const response = await axios.post("{{ route ('classroom.resources.bulk-return-submissions', [$masterClass_id, $classList_id]) }}", {
                        submissions: [{
                            id: this.selectedSubmissionId,
                            score: score
                        }],
                        return_status: 'scheduled'
                    });

                    if (response.data.success) {
                        Notiflix.Notify.success('Tugas berhasil dikembalikan');
                        location.reload();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Notiflix.Notify.failure(error.response?.data?.message || 'Gagal mengembalikan tugas');
                }
            }
        },

        scheduleReturn(submissionId) {
            this.selectedSubmissionId = submissionId;
            this.scheduleModalOpen = true;
        },

        async confirmReturn() {
            try {
                const score = document.querySelector(`input[name="submissions[${this.selectedSubmissionId}][score]"]`).value;
                
                const response = await axios.post("{{ route ('classroom.resources.bulk-return-submissions', [$masterClass_id, $classList_id]) }}", {
                    submissions: [{
                        id: this.selectedSubmissionId,
                        score: score
                    }],
                    return_status: 'now'
                });

                if (response.data.success) {
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
            // Proses penjadwalan
            this.returnSingleSubmission(this.selectedSubmissionId, 'scheduled');
            this.scheduleModalOpen = false;
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
        if (selectedSubmissions.length < 2) {
            Notiflix.Notify.failure('Pilih lebih dari satu tugas untuk pengembalian massal');
            return;
        }
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