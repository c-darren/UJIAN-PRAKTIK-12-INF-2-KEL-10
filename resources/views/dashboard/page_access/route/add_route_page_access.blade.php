@section('page_access_content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-1 px-1 mx-auto max-w-2xl lg:py-12 lg:px-1">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add A New Page Access</h2>
        <form action="#">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Page Title -->
                <div class="sm:col-span-2">
                    <label for="page_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Page Title</label>
                    <input type="text" name="page_title" id="page_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter page title" required>
                    <small class="text-gray-500">Title will be displayed in tabs</small>
                </div>

                <!-- Page URL -->
                <div class="sm:col-span-2">
                    <label for="page_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Page URL</label>
                    <input type="text" name="page_url" id="page_url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Full URL, example: /admin/dashboard" required>
                    <small class="text-gray-500">URL will determine the page's location</small>
                    <br>
                    <small class="text-gray-500">Last URL segment will be used as a prefix or as a regular page
                        <br>
                        Bila anda ingin menggunakan prefix, maka url konfigurasi akses route akan digunakan juga.
                        Urutan konfigurasi pertama yang akan digunakan adalah prefix, kemudian route.
                        <br>
                        Bila anda ingin mengatur role, group, dll yang dapat mengakses sebuah route, pastikan konfigurasi pada route terdapat juga pada prefix sehingga bisa diakses.
                    </small>
                    <br>

                </div>

                <!-- Prefix -->
                <div>
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL Type</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="prefix" selected>Prefix</option>
                        <option value="route">Route</option>
                    </select>
                    <small class="text-gray-500">Indicates if the page is prefix or not</small>
                </div>

                <!-- Method -->
                <div>
                    <label for="method" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Method</label>
                    <select id="method" name="method" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="GET" selected>GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                    <small class="text-gray-500">GET, POST, etc.</small>
                </div>

                <!-- Role -->
                <div x-data="{ isExpanded: false }" class="relative w-full col-span-2">
                    <label for="roles" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Role</label>
                
                    <!-- Container untuk checkbox -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:text-white h-32 overflow-hidden" :class="{ 'h-32': !isExpanded, 'h-64': isExpanded }">
                        <div class="flex flex-col space-y-2 overflow-auto max-h-64">
                            <!-- Gunakan foreach untuk menampilkan checkbox -->
                            @foreach($roles as $role)
                                <div>
                                    <input type="checkbox" id="{{ $role->id }}" name="roles[]" value="{{ $role->id }}" class="mr-2">
                                    <label for="{{ $role->id }}">{{ $role->role }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                    <!-- Tombol Expand/Collapse -->
                    <button type="button" @click="isExpanded = !isExpanded" class="mt-2 text-sm text-blue-500 hover:underline">
                        <span x-show="!isExpanded">Show More</span>
                        <span x-show="isExpanded">Show Less</span>
                    </button>
                </div>
                
                <!-- Bagian untuk Group -->
                <div x-data="{ isExpandedGroup: false }" class="relative w-full col-span-2 mt-4">
                    <label for="groups" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Group</label>
                
                    <div class="bg-white dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:text-white h-32 overflow-hidden" :class="{ 'h-32': !isExpandedGroup, 'h-64': isExpandedGroup }">
                        <div class="flex flex-col space-y-2 overflow-auto max-h-64">
                            @foreach($groups as $group)
                                <div>
                                    <input type="checkbox" id="{{ $group->id }}" name="groups[]" value="{{ $group->id }}" class="mr-2">
                                    <label for="{{ $group->id }}">{{ $group->group_name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                    <button type="button" @click="isExpandedGroup = !isExpandedGroup" class="mt-2 text-sm text-blue-500 hover:underline">
                        <span x-show="!isExpandedGroup">Show More</span>
                        <span x-show="isExpandedGroup">Show Less</span>
                    </button>
                </div>

                <!-- IP Address -->
                <div>
                    <label for="ip_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IP Address</label>
                    <input type="text" name="ip_address" id="ip_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter IP Address (optional)">
                    <small class="text-gray-500">IP address for access control</small>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="Enabled" selected>Enabled</option>
                        <option value="Disabled">Disabled</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <small class="text-gray-500">When the access starts</small>
                </div>

                <!-- Valid Until -->
                <div>
                    <label for="valid_until" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valid Until</label>
                    <input type="datetime-local" name="valid_until" id="valid_until" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <small class="text-gray-500">End date for access</small>
                </div>

                <!-- Group List Type -->
                <div>
                    <label for="type_group_list" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Group List Type</label>
                    <input type="text" name="type_group_list" id="type_group_list" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter type of group list" required>
                    <small class="text-gray-500">Type of access (Whitelist/Blacklist)</small>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter description (optional)"></textarea>
                </div>
            </div>

            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                Add Page Access
            </button>
        </form>
    </div>
</section>

@endsection