<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                @if(session('messageError'))
                <script>
                    Notiflix.Report.failure(
                        {!! json_encode(session('messageError.title')) !!}, 
                        {!! json_encode(session('messageError.message')) !!},
                        "Okay"
                    );
                </script>
                @endif
            
                <div id="info_creator_id_NA" class="flex items-center p-4 mb-2 mt-2 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800" user="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div class="ms-3 text-sm font-medium">
                        <span class="font-semibold">N/A:</span>
                        No data is available. This may be caused by the absence of related users or because this data hasn't been edited yet.
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#info_creator_id_NA" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div x-data="{ 
    open: false, 
    user: {}, 
    showModal(userData) { 

        this.user = userData; 
        this.open = true; 
    } 
}" 
@keydown.escape.window="open = false"
class="relative">

    <!-- Tabel Data Users -->
    <div class="flex flex-col py-1">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                @livewire('authentication.users-table')
            </div>
        </div>
    </div>

    <!-- Read More Modal -->
    <div x-show="$store.userModal.open"
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50"
        @keydown.escape.window="$store.userModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        x-data="{ 
            isOverflow: false, 
            checkOverflow() { 
                this.isOverflow = (document.querySelector('.modal-content') && document.querySelector('.modal-content').offsetHeight + 50 > window.innerHeight); 
            } 
        }"
        x-init="checkOverflow()"
        @resize.window="checkOverflow()">


        <!-- Konten Modal -->
        <div @click.away="$store.userModal.close()"
        class=" flex flex-col bg-white rounded-lg shadow dark:bg-gray-800 max-w-[70rem] w-full p-6 space-y-6 max-h-[90vh]">
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-20 p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">User Details</h3>
                    <button @click="$store.userModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="sm:flex sm:space-x-2 p-3 overflow-y-auto">
                <div class="sm:w-1/3 mr-5">
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">User ID:</p>
                        <p x-text="$store.userModal.user.id" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Name:</p>
                        <p x-text="$store.userModal.user.name" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Username:</p>
                        <p x-text="$store.userModal.user.username" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white mr-1.5 break-all">Email:</p>
                        <div class="flex items-left">
                            <span x-text="$store.userModal.user.email" class="dark:text-white mr-1.5 break-all"></span>
                            <svg x-show="$store.userModal.user.email_verified_at !== 'N/A'" class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="sm:w-1/3">
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Email Verified At:</p>
                        <p x-text="$store.userModal.user.email_verified_at !== 'N/A' ? $store.userModal.user.email_verified_at : 'Not Verified'" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Role:</p>
                        <p x-text="$store.userModal.user.role" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Created At:</p>
                        <p x-text="$store.userModal.user.created_at" class="dark:text-white"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 dark:text-white">Last Updated At:</p>
                        <p x-text="$store.userModal.user.updated_at" class="dark:text-white"></p>
                    </div>
                </div>
                <div class="sm:w-1/3 flex items-center justify-center">
                    <img :src="$store.userModal.user.avatar"
                    alt="User Avatar"
                    :class="{ 'w-24 h-24': isOverflow, 'w-32 h-32': !isOverflow }"
                    class="rounded-full mx-auto mb-4 transition-all duration-200 ease-in-out">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.userModal.close()" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="$store.editModal.open"
        x-data="editModalData()"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.editModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
    
        <div @click.away="$store.editModal.close()"
            class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h3>
                    <button @click="$store.editModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 max-h-[70vh] overflow-y-auto">
                
                <form class="w-full space-y-4" id="editUserForm" enctype='multipart/form-data' x-ref="editUserForm">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">User ID</label>
                        <input type="number" id="userId" name="id" x-model="$store.editModal.user.id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" id="fullName" name="fullName" x-model="$store.editModal.user.name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" id="username" name="username" x-model="$store.editModal.user.username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" x-model="$store.editModal.user.email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select id="roleId" name="roleId" x-model="$store.editModal.user.role_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->role }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Upload New Avatar (Optional)</label>
                        <input type="file" accept="image/*" name="avatar"
                                @change="event => {
                                    const file = event.target.files[0];
                                    if (file) {
                                        newAvatarPreview = URL.createObjectURL(file);
                                    } else {
                                        newAvatarPreview = null;
                                    }
                                }"
                                class="w-full text-sm border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <small class="text-gray-500 dark:text-gray-400">Max file size: 5MB</small>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" x-model="$store.editModal.user.resetPassword" id="resetPassword" name="resetPassword" />
                        <label for="resetPassword" class="text-sm font-medium text-gray-900 dark:text-white">Reset Password to Default</label>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" x-model="$store.editModal.user.deleteAvatar" id="deleteAvatar" name="deleteAvatar" />
                        <label for="deleteAvatar" class="text-sm font-medium text-gray-900 dark:text-white">Delete Current Avatar</label>
                    </div>            
                </form>
                
                <div class="flex flex-col items-center space-y-4">
                    <div>
                        <p class="text-sm text-gray-400 dark:text-white">Current Avatar</p>
                        <img :src="$store.editModal.user.avatar" alt="Current User Avatar" class="w-32 h-32 rounded-full mx-auto mb-2 transition-all duration-200 ease-in-out">
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 dark:text-white">New Avatar Preview</p>
                        <img :src="newAvatarPreview || $store.editModal.user.avatar" alt="New Avatar Preview" class="w-32 h-32 rounded-full mx-auto mb-2 transition-all duration-200 ease-in-out" id="avatarPreview">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.editModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="submitEditForm" class="bg-yellow-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="$store.deleteModal.open"
        x-data="deleteModalData()"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
        @keydown.escape.window="$store.deleteModal.close()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
    
        <div @click.away="$store.deleteModal.close()"
            class="bg-white dark:bg-gray-800 w-full max-w-3xl p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
            
            <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delete User</h3>
                    <button @click="$store.deleteModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:flex sm:space-x-6 p-3 overflow-y-auto max-x-[90vh]">
                <div class="sm:w-1/3 space-y-4">
                    <form class="w-full" id="deleteUserForm" x-ref="deleteUserForm">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">User ID</label>
                            <input type="number" id="userId" name="id" x-model="$store.deleteModal.user.id" x-ref="userId"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                        </div>
                    </form>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" id="fullName" name="fullName" x-model="$store.deleteModal.user.name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" id="username" name="username" x-model="$store.deleteModal.user.username"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                </div>
                <div class="sm:w-1/3 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" x-model="$store.deleteModal.user.email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <input type="text" id="role_name" name="role_name" x-model="$store.deleteModal.user.role_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" readonly>
                    </div>     
                </div>
            
                <!-- Kolom Avatar Pengguna -->
                <div class="sm:w-1/3 flex flex-col items-center space-y-4">
                    <div>
                        <p class="text-sm text-gray-400 dark:text-white">Current Avatar</p>
                        <img :src="$store.deleteModal.user.avatar" alt="Current User Avatar" class="w-32 h-32 rounded-full mx-auto mb-2 transition-all duration-200 ease-in-out">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                <button @click="$store.deleteModal.close()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button @click="submitDeleteForm" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>
    </div>
<!--END-->
</div>

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/authentication/user/view_modal.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/user/edit.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/authentication/user/delete.js') }}"></script>
@endsection