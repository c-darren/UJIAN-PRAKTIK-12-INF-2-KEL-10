@section('page_access_content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-1 px-1 mx-auto max-w-5xl lg:py-12 lg:px-1">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">Edit Route Prefix ID: {{ $prefix->id }}, Name: {{ $prefix->name }}</h2>
            <a href="{{ route('admin.page_access.route_prefix.view') }}" class="text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-4 py-2 text-center me-2 mb-2 dark:focus:ring-yellow-900 ms-auto">Back</a>
        </div>
        <div id="addRoutePrefixFormView">
            <form action="{{ route('admin.page_access.route_prefix.edit', $prefix->id) }}" method="POST" id="addRoutePrefixForm">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Prefix Name -->
                    <div class="sm:col-span-2">
                        <label for="prefix_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix Name</label>
                        <input type="text" name="prefix_name" id="prefix_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter prefix name" required value="{{ $prefix->name }}">
                        <small class="text-gray-500">Prefix names don't have to be unique</small>
                    </div>
            
                    <!-- Prefix URL -->
                    <div class="sm:col-span-2">
                        <label for="prefix_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix URL</label>
                        <input type="text" name="prefix_url" id="prefix_url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Example: /admin/dashboard/" required value="{{ $prefix->prefix }}">
                        <small class="text-gray-500">
                            Enter Full Prefix URL, 
                            <span class="text-red-500">last segment will be used as a prefix</span>.
                        </small>
                    </div>
            
                    <!-- IP Address -->
                    <div>
                        <label for="ip_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IP Address</label>
                        <input type="text" name="ip_address" id="ip_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g., 192.168.*.*" value="{{ $prefix->ip_address }}">
                        <small class="text-gray-500">Use * for IP range or add specific IP.</small>
                    </div>
            
                    <!-- Type IP Address -->
                    <div>
                        <label for="type_ip_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IP Address Type</label>
                        <select id="type_ip_address" name="type_ip_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Whitelist" @if($prefix->type_ip_address == 'Whitelist') selected @endif>Whitelist</option>
                            <option value="Blacklist" @if($prefix->type_ip_address == 'Blacklist') selected @endif>Blacklist</option>
                        </select>
                        <small class="text-gray-500">Choose access type: Whitelist or Blacklist</small>
                    </div>
            
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $prefix->start_date }}" required>
                        <small class="text-gray-500"></small>
                    </div>
            
                    <!-- Valid Until -->
                    <div>
                        <label for="valid_until" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valid Until</label>
                        <input type="datetime-local" name="valid_until" id="valid_until" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $prefix->valid_until }}">
                        <small class="text-gray-500">Leave blank for unlimited access time.</small>
                    </div>
            
                    
                    <!-- Roles -->
                    <div x-data="{ isExpanded: false }" class="relative w-full col-span-2">
                        <label for="roles" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Only For Selected Roles</label>
                        <div class="bg-white dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:text-white h-32 overflow-hidden" :class="{ 'h-32': !isExpanded, 'h-64': isExpanded }">
                            <div class="flex flex-col space-y-2 overflow-auto max-h-64">
                                @foreach($allRoles as $role)
                                <div>
                                    <input type="checkbox" id="{{ $role->id }}" name="roles[]" value="{{ $role->id }}" class="mr-2" {{ in_array($role->id, $prefix->roles->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                            <option value="Whitelist" @if($prefix->type_group_list == 'Whitelist') selected @endif>Whitelist</option>
                            <option value="Blacklist" @if($prefix->type_group_list == 'Blacklist') selected @endif>Blacklist</option>
                        </select>
                        <small class="text-gray-500">Choose access type: Whitelist or Blacklist</small>
                    </div>
                    <!-- Groups -->
                    <div x-data="{ isExpandedGroup: false }" class="relative w-full col-span-2 mt-4">
                        <label for="groups" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Only For Selected Group</label>
                        <div class="bg-white dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:text-white h-32 overflow-hidden" :class="{ 'h-32': !isExpandedGroup, 'h-64': isExpandedGroup }">
                            <div class="flex flex-col space-y-2 overflow-auto max-h-64">
                                @foreach($allGroups as $group)
                                    <div>
                                        <input type="checkbox" id="{{ $group->id }}" name="groups[]" value="{{ $group->id }}" class="mr-2" {{ in_array($group->id, $prefix->groups->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                        <textarea id="description" name="description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter description" value="{{ $prefix->description }}"></textarea>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prefix Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="Enabled" @if($prefix->status == 'Enabled') selected @endif>Enable</option>
                            <option value="Disabled" @if($prefix->status == 'Disabled') selected @endif>Disable</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between mt-4 sm:mt-6">
                    <button type="submit" id="submit_form" class="rounded-full inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Update&nbsp;
                        <b>{{ $prefix->id }}&nbsp;-&nbsp;{{ $prefix->name }}</b>
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
    <script src="{{ asset('js/page_access/route_prefix/edit.js') }}"></script>
@endsection