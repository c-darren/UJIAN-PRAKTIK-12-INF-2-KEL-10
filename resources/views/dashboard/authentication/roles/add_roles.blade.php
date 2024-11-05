@section('roles_content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-1 px-1 mx-auto max-w-5xl min-w-full sm:min-w-[640px] md:min-w-[768px] lg:min-w-[1024px] lg:py-12 lg:px-1">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add A New Route Prefix</h2>
        <div id="addRolesFormView">
            <form action="{{ route('admin.authentication.roles.store') }}" method="POST" id="addRolesForm">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Role -->
                    <div class="sm:col-span-2">
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Page Title</label>
                        <input type="text" name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter prefix name" required>
                        <small class="text-red-500">Role must be unique.</small>
                    </div> 
                    
                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" name="description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter description"></textarea>
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

    <script>
        window.redirectUrl = '{{ $redirectUrl }}';
    </script>
    <script src="{{ asset('js/authentication/role/create.js') }}"></script>