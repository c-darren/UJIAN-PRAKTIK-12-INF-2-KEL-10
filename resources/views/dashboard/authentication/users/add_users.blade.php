@section('users_content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-1 px-1 mx-auto max-w-5xl min-w-full sm:min-w-[640px] md:min-w-[768px] lg:min-w-[1024px] lg:py-12 lg:px-1">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add A New User</h2>
        <div id="addusersFormView">
            <form action="{{ route('admin.authentication.users.store') }}" method="POST" id="addUserForm" enctype="multipart/form-data">
                @csrf
            
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Full Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter full name">
                    </div>
            
                    <!-- Username -->
                    <div class="sm:col-span-2">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter username">
                        <small class="text-gray-500 dark:text-gray-400">Username must be unique.</small>
                    </div>
            
                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter email address">
                    </div>
            
                    <!-- Password -->
                    <div class="sm:col-span-2">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter password">
                    </div>
            
                    <!-- Confirm Password -->
                    <div class="sm:col-span-2">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Confirm password">
                    </div>
            
                    <!-- Role -->
                    <div class="sm:col-span-2">
                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select name="role_id" id="role_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="" disabled selected>Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    <!-- Avatar -->
                    <div class="sm:col-span-2">
                        <label for="avatar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Avatar (Optional)</label>
                        <input type="file" accept="image/jpeg, image/png, image/gif" name="avatar" id="avatar" class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        <small class="text-gray-500 dark:text-gray-400">Upload a profile picture if desired. Max file size: 5MB</small>
                    </div>
                </div>
            
                <div class="flex justify-between mt-4 sm:mt-6">
                    <button type="submit" id="submit_form" class="rounded-full inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Add User
                    </button>
                    <button type="reset" class="rounded-full inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-700 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                        Reset
                    </button>
                </div>
            </form>
                     
        </div>
    </div>
</section>
@endsection

@section('required_scripts')
    <script>
        window.redirectUrl = '{{ $redirectUrl }}';
    </script>
    <script src="{{ asset('js/authentication/user/create.js') }}"></script>
@endsection