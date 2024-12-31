<div x-data="presenceTable(@entangle('presences'))" class="p-4">
    {{-- Filters --}}
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <select x-model="selectedTopic" class="px-4 rounded-full dark:bg-gray-700 dark:text-white dark:border-gray-300">
            <option value="all">Semua Topik</option>
            <template x-for="topic in getUniqueTopics()" :key="topic.id">
                <option :value="topic.id" x-text="topic.name"></option>
            </template>
        </select>

        <input type="datetime-local" 
               x-model="startDate" 
               class="px-4 rounded-full dark:bg-gray-700 dark:text-white dark:border-gray-300">

        <input type="datetime-local" 
               x-model="endDate" 
               class="px-4 rounded-full dark:bg-gray-700 dark:text-white dark:border-gray-300">
        
        <select x-model="selectedStatus" name="status" id="status" class="px-4 rounded-full dark:bg-gray-700 dark:text-white dark:border-gray-300">
            <option value="all">Semua Status</option>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alfa">Alfa</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto shadow-xl rounded-lg">
        <table class="min-w-full bg-white dark:bg-gray-800 dark:text-white">
            <thead>
                <tr>
                    <th @click="sort('date')" class="cursor-pointer px-6 py-3 bg-gray-50 dark:bg-gray-700">
                        <div class="flex items-center justify-center">
                            Tanggal
                            <template x-if="sortColumn === 'date'">
                                <span>
                                    <template x-if="sortDirection === 'asc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                                        </svg>
                                    </template>
                                    <template x-if="sortDirection === 'desc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </template>
                                </span>
                            </template>
                        </div>
                    </th>
                    <th @click="sort('topic')" class="cursor-pointer px-6 py-3 bg-gray-50 dark:bg-gray-700">
                        <div class="flex items-center justify-center">
                            Topik
                            <template x-if="sortColumn === 'topic'">
                                <span>
                                    <template x-if="sortDirection === 'asc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                                        </svg>
                                    </template>
                                    <template x-if="sortDirection === 'desc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </template>
                                </span>
                            </template>
                        </div>
                    </th>
                    <th @click="sort('status')" class="cursor-pointer px-6 py-3 bg-gray-50 dark:bg-gray-700">
                        <div class="flex items-center justify-center">
                            Keterangan
                            <template x-if="sortColumn === 'status'">
                                <span>
                                    <template x-if="sortDirection === 'asc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                                        </svg>
                                    </template>
                                    <template x-if="sortDirection === 'desc'">
                                        <svg class="ml-1 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </template>
                                </span>
                            </template>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <template x-for="presence in filteredAndSortedData()" :key="presence.id">
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-3 text-center align-middle" x-text="formatDate(presence.date)"></td>
                        <td class="px-6 py-3 text-center align-middle" x-text="presence.topic"></td>
                        <td class="px-6 py-3 text-center align-middle">
                            <span :class="{
                                'px-2 py-1 rounded-full text-sm font-semibold text-center align-middle': true,
                                'bg-green-100 text-green-800': presence.status === 'Hadir',
                                'bg-yellow-100 text-yellow-800': presence.status === 'Izin',
                                'bg-blue-100 text-blue-800': presence.status === 'Sakit',
                                'bg-red-100 text-red-800': presence.status === 'Alfa'
                            }" x-text="presence.status"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

@section('required_scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('presenceTable', (presences, topics) => ({
        presences: presences,
        topics: topics,
        selectedTopic: 'all',
        startDate: '',
        endDate: '',
        selectedStatus: 'all',
        sortColumn: 'date',
        sortDirection: 'desc',

        getUniqueTopics() {
            const uniqueTopics = [...new Map(
                this.presences.map(item => [
                    item.topic_id, 
                    { id: item.topic_id, name: item.topic }
                ])
            ).values()];
            return uniqueTopics.sort((a, b) => a.name.localeCompare(b.name));
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        sort(column) {
            if(this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
        },

        filteredAndSortedData() {
            let data = [...this.presences];

            // Filter by topic
            if(this.selectedTopic !== 'all') {
                data = data.filter(item => item.topic_id === parseInt(this.selectedTopic));
            }
            
            // Filter by status
            if(this.selectedStatus !== 'all') {
                data = data.filter(item => item.status === this.selectedStatus);
            }
            
            // Filter by date range
            if(this.startDate) {
                data = data.filter(item => new Date(item.date) >= new Date(this.startDate));
            }
            if(this.endDate) {
                data = data.filter(item => new Date(item.date) <= new Date(this.endDate));
            }

            // Sort
            data.sort((a, b) => {
                let compareValue = 0;
                if(this.sortColumn === 'date') {
                    compareValue = new Date(a.date) - new Date(b.date);
                } else if(this.sortColumn === 'topic') {
                    compareValue = a.topic.localeCompare(b.topic);
                } else if(this.sortColumn === 'status') {
                    compareValue = a.status.localeCompare(b.status);
                }
                return this.sortDirection === 'asc' ? compareValue : -compareValue;
            });

            return data;
        }
    }));
});
</script>
@endsection
