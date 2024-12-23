<div x-data="submissionManager" class="flex h-[calc(100vh-200px)]">
    <!-- Bagian Kiri - Daftar Peserta (30%) -->
    <div class="w-[30%] border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
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
                    class="w-full text-white bg-primary-700 disabled:bg-gray-400 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Kembalikan Terpilih
                </button>
            </div>
        </div>

        <!-- Submitted List -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="p-4 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Telah Mengumpulkan ({{ $submissions->count() }})</h3>
            </div>
            
            @foreach($submissions as $submission)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer relative" 
                 data-submission-id="{{ $submission->id }}"
                 @click="showSubmission({{ $submission->id }})">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="selected_submissions[]" 
                               value="{{ $submission->id }}" 
                               class="submission-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                               @click.stop>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->student_name }}</h4>
                            <div class="flex items-center mt-1">
                                <input type="number" 
                                       name="submissions[{{ $submission->id }}][score]" 
                                       value="{{ $submission->score }}" 
                                       min="0" max="100" 
                                       step="0.01"
                                       class="w-16 text-sm p-1 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       @click.stop
                                       @blur="saveDraft({{ $submission->id }}, $event.target.value)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="dropdown-container relative" @click.stop>
                        <button type="button" 
                                @click="toggleDropdown({{ $submission->id }})"
                                class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-sm p-1.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                            </svg>
                        </button>
                        <div x-show="openDropdown === {{ $submission->id }}"
                             @click.away="closeDropdowns()"
                             class="absolute right-0 z-10 mt-2 w-44 bg-white rounded-lg shadow-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                            <ul class="py-1">
                                <li>
                                    <button type="button"
                                            @click="openReturnModal({{ $submission->id }})" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">
                                        Kembalikan
                                    </button>
                                </li>
                            </ul>
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

        <!-- Non-submitting Students -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="p-4 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Belum Mengumpulkan ({{ $nonSubmittingStudents->count() }})</h3>
            </div>
            
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

    <!-- Bagian Kanan - Preview Tugas (70%) -->
    <div class="w-[70%] bg-gray-50 dark:bg-gray-800">
        <div id="submissionPreview" class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
            Pilih peserta didik untuk melihat tugasnya
        </div>
    </div>

    <!-- Include the modal component -->
    @include('dashboard.classroom.class_list.resource.partials.return-modal')
</div>

@section('required_scripts')
<script>
let currentSubmissionId = null;
let autoSaveTimeout = null;

// Initialize all dropdowns and modals
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');
    dropdownButtons.forEach(button => {
        new Dropdown(button);
    });

    const returnModal = new Modal(document.getElementById('returnModal'));
    window.returnModal = returnModal;

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip-target]');
    tooltips.forEach(tooltip => {
        new Tooltip(tooltip);
    });
});

// Show submission preview
async function showSubmission(submissionId) {
    try {
        // Highlight selected submission
        document.querySelectorAll('[data-submission-id]').forEach(el => {
            el.classList.remove('bg-gray-100', 'dark:bg-gray-700');
        });
        document.querySelector(`[data-submission-id="${submissionId}"]`).classList.add('bg-gray-100', 'dark:bg-gray-700');

        // Fetch submission data
        const response = await axios.get(`/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/preview`);
        
        // Update preview area
        const preview = document.getElementById('submissionPreview');
        preview.innerHTML = response.data.html;
        
        // Add loading state if needed
        preview.classList.remove('animate-pulse');
    } catch (error) {
        console.error('Error loading submission:', error);
    }
}

async function saveDraft(submissionId, score) {
    try {
        const response = await axios.post(
            `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/grade`,
            {
                score: score,
                return_status: 'draft'
            }
        );

        // Tampilkan indikator tersimpan
        const submissionDiv = document.querySelector(`[data-submission-id="${submissionId}"]`);
        const savedIndicator = submissionDiv.querySelector('.saved-indicator') || createSavedIndicator();
        submissionDiv.appendChild(savedIndicator);
        
        // Hilangkan indikator setelah 2 detik
        setTimeout(() => savedIndicator.remove(), 2000);
    } catch (error) {
        console.error('Error saving draft:', error);
        Notiflix.Notify.failure('Gagal menyimpan nilai');
        // Tampilkan pesan error jika perlu
    }
}

function createSavedIndicator() {
    const div = document.createElement('div');
    div.className = 'saved-indicator text-xs text-green-500 mt-1';
    div.textContent = 'Tersimpan';
    return div;
}

async function showReturnOptions(submissionId) {
    currentSubmissionId = submissionId;
    const modal = new Modal(document.getElementById('returnModal'));
    modal.show();
}

document.getElementById('returnType').addEventListener('change', function() {
    const scheduledDiv = document.getElementById('scheduledReturnDiv');
    scheduledDiv.classList.toggle('hidden', this.value !== 'scheduled');
});

// Event handler untuk tombol konfirmasi di modal
document.getElementById('confirmReturn').addEventListener('click', async function() {
    const returnType = document.getElementById('returnType').value;
    const scheduledTime = document.getElementById('scheduledReturnTime').value;
    const score = document.querySelector(`input[name="submissions[${currentSubmissionId}][score]"]`).value;
    const feedback = document.querySelector(`#feedback-${currentSubmissionId}`).value;
    
    if (!score) {
        Notiflix.Notify.failure('Nilai harus diisi');
        return;
    }
    
    try {
        const response = await axios.post(
            `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${currentSubmissionId}/grade`,
            {
                score: score,
                feedback: feedback,
                return_status: returnType === 'scheduled' ? 'scheduled' : 'returned',
                scheduled_return_at: returnType === 'scheduled' ? scheduledTime : null
            }
        );

        Notiflix.Notify.success('Nilai berhasil dikembalikan');
        const modal = Modal.getInstance(document.getElementById('returnModal'));
        modal.hide();

        // Update status display
        const submissionDiv = document.querySelector(`[data-submission-id="${currentSubmissionId}"]`);
        const statusText = returnType === 'scheduled' 
            ? `Dijadwalkan: ${new Date(scheduledTime).toLocaleString('id-ID')}`
            : `Dikembalikan: ${new Date().toLocaleString('id-ID')}`;
        submissionDiv.querySelector('.text-xs').textContent = statusText;

    } catch (error) {
        console.error('Error returning grade:', error);
        Notiflix.Notify.failure(error.response?.data?.message || 'Gagal mengembalikan nilai');
    }
});

async function updateScore(submissionId, score) {
    try {
        const response = await axios.post(
            `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/update-score`,
            { 
                score: score,
                return_status: 'draft'
            }
        );

        if (response.data.success) {
            Notiflix.Notify.success('Nilai tersimpan');
            const input = document.querySelector(`input[name="submissions[${submissionId}][score]"]`);
            showSavedIndicator(input);
        }
    } catch (error) {
        console.error('Error updating score:', error);
        Notiflix.Notify.failure('Gagal menyimpan nilai');
    }
}

// Feedback save function
async function saveFeedback(submissionId, feedback) {
    try {
        const response = await axios.post(
            `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/grade`,
            {
                score: document.querySelector(`input[name="submissions[${submissionId}][score]"]`).value,
                feedback: feedback,
                return_status: 'draft'
            }
        );

        if (response.data.success) {
            Notiflix.Notify.success('Feedback tersimpan');
        }
    } catch (error) {
        console.error('Error saving feedback:', error);
        Notiflix.Notify.failure('Gagal menyimpan feedback');
    }
}

// Show saved indicator helper
function showSavedIndicator(element) {
    const container = element.parentElement;
    const savedIndicator = document.createElement('div');
    savedIndicator.className = 'saved-indicator text-xs text-green-500 absolute -bottom-5 left-0';
    savedIndicator.textContent = 'Tersimpan';
    
    container.style.position = 'relative';
    container.appendChild(savedIndicator);
    
    setTimeout(() => savedIndicator.remove(), 2000);
}

// Update dropdowns to use custom implementation
function toggleDropdown(submissionId) {
    const dropdown = document.getElementById(`dropdown-${submissionId}`);
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-container .hidden').forEach(el => {
        if (el.id !== `dropdown-${submissionId}`) {
            el.classList.add('hidden');
        }
    });
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-container .hidden').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function(e) {
    document.querySelectorAll('.submission-checkbox').forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
    updateBulkReturnButton();
});

// Update bulk return button state
function updateBulkReturnButton() {
    const checkedBoxes = document.querySelectorAll('.submission-checkbox:checked').length;
    document.getElementById('bulkReturnBtn').disabled = checkedBoxes === 0;
}

// Add listener to all checkboxes
document.querySelectorAll('.submission-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkReturnButton);
});

// Replace SweetAlert with Notiflix
function showNotification(type, message) {
    if (type === 'success') {
        Notiflix.Notify.success(message);
    } else if (type === 'error') {
        Notiflix.Notify.failure(message);
    }
}

// Update existing functions to use Notiflix
async function updateScore(submissionId, score) {
    try {
        const response = await axios.post(
            `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${submissionId}/update-score`,
            { score: score }
        );

        if (response.data.success) {
            showNotification('success', 'Nilai tersimpan');
        }
    } catch (error) {
        console.error('Error updating score:', error);
        showNotification('error', error.response?.data?.message || 'Gagal menyimpan nilai');
    }
}

// ...rest of your existing script with Swal.fire replaced by showNotification...
</script>
@endsection

@push('styles')
<style>
.dropdown-container {
    position: relative;
    display: inline-block;
}

/* ...existing styles... */
</style>
@endpush

@section('required_scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('submissions', {
            currentId: null,
            openDropdown: null,
            isModalOpen: false,
            returnType: 'now',
            scheduledTime: null,
            scoreUpdateTimeout: null,

            toggleDropdown(id) {
                this.openDropdown = this.openDropdown === id ? null : id;
            },

            openModal(id) {
                this.currentId = id;
                this.isModalOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isModalOpen = false;
                this.currentId = null;
                document.body.style.overflow = '';
            },

            async submitGrade() {
                const score = document.querySelector(`input[name="submissions[${this.currentId}][score]"]`).value;
                if (!score) {
                    Notiflix.Notify.failure('Nilai harus diisi');
                    return;
                }

                try {
                    const response = await axios.post(
                        `/classroom/{{ $masterClass_id }}/{{ $classList_id }}/resources/submissions/${this.currentId}/grade`,
                        {
                            score: score,
                            feedback: document.querySelector(`#feedback-${this.currentId}`)?.value,
                            return_status: 'draft',
                            scheduled_return_at: this.returnType === 'scheduled' ? this.scheduledTime : null
                        }
                    );

                    Notiflix.Notify.success('Nilai berhasil dikembalikan');
                    this.closeModal();

                    // Update status display
                    const submissionDiv = document.querySelector(`[data-submission-id="${this.currentId}"]`);
                    const statusText = this.returnType === 'scheduled' 
                        ? `Dijadwalkan: ${new Date(this.scheduledTime).toLocaleString('id-ID')}`
                        : `Dikembalikan: ${new Date().toLocaleString('id-ID')}`;
                    submissionDiv.querySelector('.text-xs').textContent = statusText;

                } catch (error) {
                    console.error('Error returning grade:', error);
                    Notiflix.Notify.failure(error);
                }
            }
        });
    });

    // Alpine.js component
    document.addEventListener('alpine:init', () => {
        Alpine.data('submissionManager', () => ({
            init() {
                this.$watch('$store.submissions.returnType', value => {
                    const scheduledDiv = document.getElementById('scheduledReturnDiv');
                    scheduledDiv.classList.toggle('hidden', value !== 'scheduled');
                });
            },

            toggleDropdown(event, id) {
                event.stopPropagation();
                Alpine.store('submissions').toggleDropdown(id);
            },

            openReturnModal(id) {
                Alpine.store('submissions').openModal(id);
            },

            closeModal() {
                Alpine.store('submissions').closeModal();
            }
        }));
    });
</script>
@endsection
```
