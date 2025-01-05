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
            $attachmentUrl = route('classroom.resources.view-attachment', [
                'masterClass_id'   => $masterClass_id,
                'class_id'         => $classList->id,
                'type'             => $resource_type,
                'resource_id'      => $resource_id,
                'attachment_index' => $idx,
            ]);

            $downloadUrl = route('classroom.resources.download-attachment', [
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
        editMode: false, 
        formData: @json($formData),
        deleteAttachments: [],
        actionUrl: "{{ route('classroom.resources.update', [$masterClass_id, $classList->id, $resource_type, $resource_id]) }}",
        deleteAttachmentsList: [],
        // Method untuk menandai atau membatalkan penandaan penghapusan lampiran
        toggleDeleteAttachment(path) {
            if (this.deleteAttachmentsList.includes(path)) {
                // Jika sudah ditandai untuk dihapus, batalkan penandaan
                this.deleteAttachmentsList = this.deleteAttachmentsList.filter(p => p !== path);
            } else {
                // Tandai untuk dihapus
                this.deleteAttachmentsList.push(path);
            }
        },

        handleNewAttachments(evt) {
            this.formData.new_attachments = evt.target.files;
        },
        
        {{-- deleteAttachment(index) {
            this.deleteAttachments.push(index);
            this.existingAttachments = this.existingAttachments.filter(att => att.index !== index);
        }, --}}
        
        submitForm() {
            let fd = new FormData();
            fd.append("topic_id", this.formData.topic_id);

            @if($resource_type === 'material')
                fd.append("material_name", this.formData.resource_name);
                fd.append("start_date", this.formData.start_date);
            @else
                fd.append("assignment_name", this.formData.resource_name);
                fd.append("start_date", this.formData.start_date);
                fd.append("end_date", this.formData.end_date);
                fd.append("accept_late_submissions", this.formData.accept_late_submissions ? "1" : "0");
            @endif

            fd.append("description", this.formData.description);

            // Lampiran baru
            if (this.formData.new_attachments.length > 0) {
                for (let i = 0; i < this.formData.new_attachments.length; i++) {
                    fd.append("attachment[]", this.formData.new_attachments[i]);
                }
            }

            // Lampiran yang dihapus
            {{-- if (this.deleteAttachments.length > 0) {
                for (let i = 0; i < this.deleteAttachments.length; i++) {
                    fd.append("delete_attachments[]", this.deleteAttachments[i]);
                }
            } --}}

            if (this.deleteAttachmentsList.length > 0) {
                this.deleteAttachmentsList.forEach(path => {
                    fd.append("delete_attachments[]", path);
                });
            }

            // Kirim _method PUT untuk Laravel
            fd.append("_method", "PUT");

            // Kirim request menggunakan Axios
            axios.post(this.actionUrl, fd, {
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then(res => {
                if(res.data.resource) {
                    Notiflix.Notify.success("Berhasil memperbarui data!");
                    window.location.reload();
                } else {
                    Notiflix.Notify.failure("Gagal memperbarui data.");
                }
            })
            .catch(err => {
                let msg = err.response?.data?.message || "Terjadi kesalahan.";
                Notiflix.Notify.failure(msg);
            });
        }
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
            <!-- Judul: Tergantung editMode -->
            <template x-if="!editMode">
                <h2 class="text-xl font-medium text-gray-900 dark:text-white title-font mb-2">
                    {{ $resource_name }}
                </h2>
            </template>
            <template x-if="editMode">
                <!-- Versi Input: Resource Name -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        @if($resource_type === 'material')
                            Judul Materi
                        @else
                            Judul Tugas
                        @endif
                    </label>
                    <input 
                        type="text" 
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                        x-model="formData.resource_name"
                    >
                </div>
            </template>

            <!-- DESKRIPSI: Tergantung editMode -->
            <template x-if="!editMode">
                <div class="relative overflow-x-auto shadow-xl sm:rounded-lg lg:max-h-[70vh] max-h-[60vh] overflow-y-auto p-2.5">
                    <p class="dark:text-white leading-relaxed mb-2 text-sm text-justify">
                        {{ $description }}
                    </p>
                </div>
            </template>
            <template x-if="editMode">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                    <textarea 
                        rows="4"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                        x-model="formData.description">
                    </textarea>
                </div>
            </template>

            <!-- Menampilkan Attachments Lama -->
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Lampiran
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($existingAttachments as $attachment)
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
                            <!-- Tombol hapus (tanda silang) hanya muncul di edit mode -->
                            <template x-if="editMode">
                                <button
                                    type="button"
                                    class="absolute top-0 right-0 mt-1 mr-1 text-red-700 hover:text-red-900 
                                           w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-700 rounded-full text-lg font-bold"
                                    @click.prevent="toggleDeleteAttachment(@js($attachment['path']))">
                                    <!-- Tampilkan '×' jika belum ditandai untuk dihapus, '↺' jika sudah -->
                                    <span x-text="deleteAttachmentsList.includes(@js($attachment['path'])) ? '↺' : '×'"></span>
                                </button>
                            </template>
                            <!-- Hidden input untuk mengirim data ke server -->
                            <template x-if="isDeleted">
                                <input type="hidden" name="delete_attachments[]" value="{{ $attachment['path'] }}">
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Menambahkan Attachments Baru (jika editMode) -->
            <template x-if="editMode">
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Lampiran Baru</label>
                    <input 
                        type="file" 
                        multiple 
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 
                               rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-600 dark:text-white"
                        @change="handleNewAttachments($event)"
                    >
                </div>
            </template>
        </div>

        <!-- BAGIAN KANAN (Detail + Tombol Edit/Save) -->
        <div class="lg:w-1/4 md:mb-0 mb-6 flex-shrink-0 flex flex-col md:ml-2 w-full">
            <div class="w-full flex items-center justify-between">
                <span class="font-semibold title-font dark:text-white text-gray-700">Detail</span>
                <template x-if="!editMode">
                    <!-- Tombol Edit -->
                    <button 
                        class="bg-yellow-500 w-1/4 text-white rounded-xl h-10 flex items-center justify-center font-bold"
                        @click="editMode = true">
                        Edit
                    </button>
                </template>
                <template x-if="editMode">
                    <!-- Tombol Save -->
                    <button 
                        class="bg-green-600 w-1/4 text-white rounded-xl h-10 flex items-center justify-center font-bold"
                        @click="submitForm"
                        data-actionUrl="{{ route('classroom.resources.update', [$masterClass_id, $classList->id, $resource_type, $resource_id]) }}">
                        Save
                    </button>
                </template>
            </div>

            <!-- Topik: Tergantung editMode -->
            <template x-if="editMode">
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Topik
                    </label>
                    <select 
                        name="topic_id"
                        id="topic_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 
                               rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-600 dark:text-white"
                        x-model="formData.topic_id">
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}">{{ $topic->topic_name }}</option>
                        @endforeach
                    </select>
                </div>
            </template>
            <template x-if="!editMode">
                <span class="mt-1 text-gray-500 dark:text-white text-sm">
                    Topik: {{ $topic_name }}
                </span>
            </template>

            <!-- Start Date: Tergantung editMode -->
            <template x-if="!editMode">
                <span class="mt-1 text-gray-500 dark:text-white text-sm">
                    Tanggal Mulai: {{ $formatted_start_date }}
                </span>
            </template>
            <template x-if="editMode">
                <div class="mt-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tanggal Mulai
                    </label>
                    <input 
                        type="datetime-local" 
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 
                               rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-600 dark:text-white"
                        x-model="formData.start_date"
                    >
                </div>
            </template>

            <!-- Bagian Assignment Only -->
            @if($resource_type == 'assignment')
                <!-- End Date: Tergantung editMode -->
                <template x-if="!editMode">
                    <span class="mt-1 text-gray-500 dark:text-white text-sm">
                        Tenggat Waktu: {{ $formatted_end_date }}
                    </span>
                </template>
                <template x-if="editMode">
                    <div class="mt-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tenggat Waktu
                        </label>
                        <input 
                            type="datetime-local" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 
                                   rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 
                                   dark:bg-gray-600 dark:text-white"
                            x-model="formData.end_date"
                        >
                    </div>
                </template>

                <!-- Accept Late Submissions: Tergantung editMode -->
                <template x-if="!editMode">
                    <span class="mt-1 text-gray-500 dark:text-white text-sm">
                        Terima Pengumpulan Terlambat: 
                        @if($accept_late_submissions == 1) Ya @else Tidak @endif
                    </span>
                </template>
                <template x-if="editMode">
                    <div class="mt-2 flex items-center space-x-2">
                        <input 
                            type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            x-model="formData.accept_late_submissions">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Terima Pengumpulan Terlambat
                        </span>
                    </div>
                </template>
            @endif

            {{-- Timestamps & Author --}}
            <span class="mt-1 text-gray-500 dark:text-white text-sm">
                Dibuat: {{ $created_at }}
            </span>
            <span class="mt-1 text-gray-500 dark:text-white text-sm">
                Dibuat oleh: {{ $author }}
            </span>
            <span class="mt-1 text-gray-500 dark:text-white text-sm">
                Terakhir Diperbarui oleh: {{ $editor }}
            </span>
            <span class="mt-1 text-gray-500 dark:text-white text-sm">
                Terakhir Diperbarui: {{ $updated_at }}
            </span>
        </div>
        
    </div>

    {{-- Preview Attachment Modal --}}
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        id="previewModal" 
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4 max-w-4xl w-full max-h-[100vh] overflow-auto relative">
            <div class="flex justify-between items-center p-4 border-b dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">Document</h3>
                <div class="items-right">
                    <button id="downloadBtn">                        
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V4M7 14H5a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2m-1-5-4 5-4-5m9 8h.01"/>
                        </svg>
                    </button>
                        
                    <button id="openInNewTabBtn">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/>
                            Open In New Tab                      
                        </svg>
                    </button>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="modalContent" class="flex items-center justify-center">
                <!-- Document -->
            </div>
        </div>
    </div>
    <div class="">
        <hr class="mt-2 mb-1">
        <span class="font-semibold title-font dark:text-white text-gray-700">Komentar Kelas</span>
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
@endsection

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/class_list/resource_view/modal_file.js') }}"></script>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('comment-posted', () => {
            Notiflix.Notify.success('Komentar berhasil ditambahkan!');
        });
    });
</script>
@endsection