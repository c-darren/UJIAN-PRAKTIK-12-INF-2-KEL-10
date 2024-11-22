@extends('dashboard.components.layout')
@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="flex flex-col items-center py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Change Password</h2>
        <form action="{{ route('profile.changepassword.update') }}" method="POST" enctype="multipart/form-data" id="password-update-form" class="w-full">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Current Password -->
                <div class="sm:col-span-2">
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter current password">
                </div>

                <!-- New Password -->
                <div class="sm:col-span-2">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter new password">
                </div>

                <!-- Confirm Password -->
                <div class="sm:col-span-2">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Confirm new password">
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit" class="rounded-full inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-300 focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900">Change Password</button>
            </div>
        </form>
        <!-- End of modal -->
    </div>
</section>
@endsection

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/profile/update_password.js') }}"></script>
@endsection
