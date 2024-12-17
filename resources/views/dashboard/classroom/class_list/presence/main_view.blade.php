@extends('dashboard.components.layout')

@section('title', $page_title)

@section('content')
<!-- Bagian Menampilkan Informasi Daftar Hadir -->
<div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 rounded-lg shadow dark:border-gray-700 mb-6">
    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600 mb-2">
        <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Informasi Daftar Hadir</h3>
        
        <!-- Kolom 1 -->
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1">
                <p class="text-sm text-gray-700 dark:text-gray-300">Topic: {{ $attendance->topic->topic_name }}</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Date: {{ $attendance->attendance_date }}</p>
            </div>
    
            <!-- Kolom 2 -->
            <div class="col-span-1">
                <p class="text-sm text-gray-700 dark:text-gray-300">Created: {{ $attendance->created_at }}</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Edited: {{ $attendance->edited_at ?? 'Belum pernah diedit' }}</p>
            </div>
        </div>
    
        <!-- Kolom 1 + 2 (Deskripsi) -->
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-4">{{ $attendance->description }}</p>
    </div>
    
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <!-- Izin -->
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600 w-full">
            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Izin: {{ count($izin) }} orang</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($izin as $key => $presence)
                    @if($key % 2 == 0) <!-- Kolom pertama -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @else <!-- Kolom kedua -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Sakit -->
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600 w-full">
            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Sakit: {{ count($sakit) }} orang</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($sakit as $key => $presence)
                    @if($key % 2 == 0) <!-- Kolom pertama -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @else <!-- Kolom kedua -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Alfa -->
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600 w-full">
            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Alfa: {{ count($alfa) }} orang</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($alfa as $key => $presence)
                    @if($key % 2 == 0) <!-- Kolom pertama -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @else <!-- Kolom kedua -->
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>{{ $presence->student->name }}</strong>
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Tabel Presensi dengan Fitur Pencarian -->
<div x-data="presenceTable()" class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4 dark:text-white">{{ $page_title }}</h2>
    
    <!-- Input Pencarian -->
    <div class="mb-4">
        <input 
            type="text" 
            placeholder="Cari nama peserta didik..." 
            x-model="search" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
    </div>

    <div class="overflow-x-auto shadow-xl">
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead>
                <tr>
                    <!-- Kolom Nama dengan Sortable -->
                    <th id="name-column"
                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                        @click="sortBy('name')"
                    >
                        Nama
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="inline w-4 h-4 ml-1" 
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  :d="sortedOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                        </svg>
                    </th>
                    <!-- Kolom Hadir -->
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Hadir
                    </th>
                    <!-- Kolom Izin -->
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Izin
                    </th>
                    <!-- Kolom Sakit -->
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Sakit
                    </th>
                    <!-- Kolom Alfa -->
                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Alfa
                    </th>
                </tr>
            </thead>
            <tbody>
                <template x-for="user in filteredUsers()" :key="user.id">
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" :data-user-id="user.id">
                        <!-- Kolom Nama -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            <span x-text="user.name"></span>
                        </td>
                        <!-- Kolom Hadir -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button 
                                @click="setStatus(user.id, 'Hadir')"
                                :class="presences[user.id] === 'Hadir' ? 'bg-green-600 text-white' : 'bg-transparent border border-green-600 text-green-600 dark:text-green-400'"
                                class="px-3 py-1 rounded-md transition-colors duration-500"
                            >
                                Hadir
                            </button>
                        </td>
                        <!-- Kolom Izin -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button 
                                @click="setStatus(user.id, 'Izin')"
                                :class="presences[user.id] === 'Izin' ? 'bg-yellow-400 text-white' : 'bg-transparent border border-yellow-400 text-yellow-400 dark:text-yellow-200'"
                                class="px-3 py-1 rounded-md transition-colors duration-500"
                            >
                                Izin
                            </button>
                        </td>
                        <!-- Kolom Sakit -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button 
                                @click="setStatus(user.id, 'Sakit')"
                                :class="presences[user.id] === 'Sakit' ? 'bg-blue-500 text-white' : 'bg-transparent border border-blue-500 text-blue-500 dark:text-blue-300'"
                                class="px-3 py-1 rounded-md transition-colors duration-500"
                            >
                                Sakit
                            </button>
                        </td>
                        <!-- Kolom Alfa -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button 
                                @click="setStatus(user.id, 'Alfa')"
                                :class="presences[user.id] === 'Alfa' ? 'bg-red-500 text-white' : 'bg-transparent border border-red-500 text-red-500 dark:text-red-300'"
                                class="px-3 py-1 rounded-md transition-colors duration-500"
                            >
                                Alfa
                            </button>
                        </td>
                    </tr>
                </template>
                <tr x-show="filteredUsers().length === 0">
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-300">
                        Tidak ada peserta didik tersedia.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tombol Simpan Perubahan -->
    <div class="mt-4 flex justify-end">
        <button 
            @click="savePresences" 
            data-actionUrl="{{ route('classroom.presence.bulkUpdate', [$masterClass_id, $classList->id, $attendance_id]) }}"
            data-redirectUrl="{{ route('classroom.attendance.index', [$masterClass_id, $classList->id, $attendance_id]) }}" 
            class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700"
        >
            Simpan Perubahan
        </button>
    </div>
</div>
@endsection

@section('required_scripts')
<script>
    function presenceTable() {
        return {
            // Inisialisasi data users dan presences
            users: @json($users),
            presences: {
                @foreach($users as $user)
                    '{{ $user['id'] }}': '{{ $presences->get($user['id'])->status ?? 'Hadir' }}',
                @endforeach
            },
            search: '', // Properti untuk menyimpan kata kunci pencarian
            sortedOrder: 'desc', // Properti untuk mengelola arah sorting

            /**
             * Mengatur status presensi untuk user tertentu
             * @param {string} userId
             * @param {string} status
             */
            setStatus(userId, status) {
                this.presences[userId] = status;
            },

            /**
             * Mengirim data presensi yang diperbarui ke server
             */
            savePresences() {
                const actionUrl = document.querySelector('[data-actionUrl]').getAttribute('data-actionUrl');
                const redirectUrl = document.querySelector('[data-redirectUrl]').getAttribute('data-redirectUrl');
                const presencesArray = Object.keys(this.presences).map(userId => ({
                    user_id: userId,
                    status: this.presences[userId],
                }));

                axios.put(actionUrl, {
                    presences: presencesArray,
                })
                .then(response => {
                    if (response.data.success) {
                        // Tampilkan notifikasi sukses menggunakan Notiflix
                        Notiflix.Report.success(
                            'Berhasil!',
                            response.data.message,
                            'OK'
                        )
                        // Redirect ke classroom.attendance.index setelah 1 detik
                        setTimeout(() => {
                            window.location.replace(redirectUrl);
                        }, 1000);
                    } else {
                        // Tampilkan notifikasi gagal menggunakan Notiflix
                        Notiflix.Report.failure(
                            'Gagal!',
                            response.data.message,
                            'OK'
                        )
                    }
                })
                .catch(error => {
                    // Tampilkan notifikasi error menggunakan Notiflix
                    Notiflix.Report.failure(
                        'Error!',
                        'Terjadi kesalahan saat memperbarui presensi.',
                        'OK'
                    )
                    console.error(error);
                });
            },

            /**
             * Mengurutkan tabel berdasarkan kolom tertentu
             * @param {string} column - Nama kolom yang akan diurutkan
             */
            sortBy(column) {
                if (column === 'name') {
                    // Membuat salinan array untuk menghindari reaktivitas masalah
                    const sortedUsers = [...this.users].sort((a, b) => {
                        let nameA = a.name.toLowerCase();
                        let nameB = b.name.toLowerCase();
                        if (nameA < nameB) return this.sortedOrder === 'asc' ? -1 : 1;
                        if (nameA > nameB) return this.sortedOrder === 'asc' ? 1 : -1;
                        return 0;
                    });
                    // Reassign users array secara reaktif
                    this.users = sortedUsers;
                    // Toggle arah sorting
                    this.sortedOrder = this.sortedOrder === 'asc' ? 'desc' : 'asc';
                }
            },

            /**
             * Mengembalikan daftar pengguna yang sudah difilter berdasarkan kata kunci pencarian
             * @returns {Array} - Daftar pengguna yang sudah difilter
             */
            filteredUsers() {
                if (this.search.trim() === '') {
                    return this.users;
                }
                return this.users.filter(user => 
                    user.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
        }
    }
</script>
@endsection