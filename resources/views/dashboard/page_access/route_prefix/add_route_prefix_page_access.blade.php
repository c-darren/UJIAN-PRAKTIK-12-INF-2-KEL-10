@section('page_access_content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-1 px-1 mx-auto max-w-5xl min-w-full sm:min-w-[640px] md:min-w-[768px] lg:min-w-[1024px] lg:py-12 lg:px-1">
    {{-- <div class="py-1 px-1 mx-auto max-w-5xl lg:py-12 lg:px-1"> --}}
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add A New Route Prefix</h2>
        <div id="addRoutePrefixFormView">
            <form action="{{ route('admin.page_access.route_prefix.store') }}" method="POST" id="addRoutePrefixForm">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Prefix Name -->
                    <div class="sm:col-span-2">
                        <label for="prefix_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix Name</label>
                        <input type="text" name="prefix_name" id="prefix_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter prefix name" required>
                        <small class="text-gray-500">Prefix names don't have to be unique.</small>
                    </div>
            
                    <!-- Prefix URL -->
                    <div class="sm:col-span-2">
                        <label for="prefix_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix URL</label>
                        <input type="text" name="prefix_url" id="prefix_url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Example: /admin/dashboard/" required>
                        <small class="text-gray-500">
                            Enter Full Prefix URL, 
                            <span class="text-red-500">last segment will be used as a prefix</span>.
                        </small>
                    </div>
            
                    <!-- IP Address -->
                    <div>
                        <label for="ip_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IP Address</label>
                        <input type="text" name="ip_address" id="ip_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 192.168.*.*">
                        <small class="text-gray-500">Use * for IP range or add specific IP.</small>
                    </div>
            
                    <!-- Type IP Address -->
                    <div>
                        <label for="type_ip_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IP Address Type</label>
                        <select id="type_ip_address" name="type_ip_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Whitelist">Whitelist</option>
                            <option value="Blacklist">Blacklist</option>
                        </select>
                        <small class="text-gray-500">Choose access type: Whitelist or Blacklist</small>
                    </div>
            
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ now() }}" readonly required>
                        <small class="text-gray-500"></small>
                    </div>
            
                    <!-- Valid Until -->
                    <div>
                        <label for="valid_until" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valid Until</label>
                        <input type="datetime-local" name="valid_until" id="valid_until" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <small class="text-gray-500">Leave blank for unlimited access.</small>
                    </div>
            
                    
                    <!-- Roles -->
                    <div x-data="{ isExpanded: false }" class="relative w-full col-span-2">
                        <label for="roles" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Only For Selected Roles</label>
                        <div class="bg-white dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:text-white h-32 overflow-hidden" :class="{ 'h-32': !isExpanded, 'h-64': isExpanded }">
                            <div class="flex flex-col space-y-2 overflow-auto max-h-64">
                                @foreach($roles as $role)
                                <div>
                                    <input type="checkbox" id="{{ $role->id }}" name="roles[]" value="{{ $role->id }}" class="mr-2">
                                    <label for="{{ $role->id }}">{{ $role->role }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" @click="isExpanded = !isExpanded" class="mt-2 text-sm text-blue-500 hover:underline">
                            <span x-show="!isExpanded">Show More</span>
                            <span x-show="isExpanded">Show Less</span>
                        </button>
                    </div>
                    
                    <!-- Type Group List -->
                    <div class="sm:col-span-2">
                        <label for="type_group_list" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Group List Type</label>
                        <select id="type_group_list" name="type_group_list" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Whitelist">Whitelist</option>
                            <option value="Blacklist">Blacklist</option>
                        </select>
                        <small class="text-gray-500">Choose access type: Whitelist or Blacklist</small>
                    </div>
                    <!-- Groups -->
                    <div x-data="{ isExpandedGroup: false }" class="relative w-full col-span-2 mt-4">
                        <label for="groups" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Only For Selected Group</label>
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
            
                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" name="description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter description"></textarea>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Enabled">Enable</option>
                            <option value="Disabled">Disable</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between mt-4 sm:mt-6">
                    <button type="submit" id="submit_form" class="rounded-full inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Add Access Route
                    </button>
                    <button type="reset" class="rounded-full inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-red-700 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        window.redirectUrl = '{{ $redirectUrl }}';
    </script>
    <script src="{{ asset('js/page_access/route_prefix/create.js') }}"></script>
@endsection