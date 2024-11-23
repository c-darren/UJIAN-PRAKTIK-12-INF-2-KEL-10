@extends('dashboard.components.layout')
@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="flex flex-col items-center py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Change Password</h2>
        <form action="{{ route('profile.changepassword.update') }}" method="POST" id="password-update-form" class="w-full">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Current Password -->
                <div class="sm:col-span-2 relative">
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                           placeholder="Enter current password">
                    <button type="button" class="absolute top-10 right-3 text-sm text-gray-600 dark:text-gray-300 toggle-password" data-target="#current_password">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="eye-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
        
                <!-- New Password -->
                <div class="sm:col-span-2 relative">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                    <input type="password" name="password" id="password" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                           placeholder="Enter new password">
                    <button type="button" class="absolute top-10 right-3 text-sm text-gray-600 dark:text-gray-300 toggle-password" data-target="#password">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="eye-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
        
                <!-- Confirm Password -->
                <div class="sm:col-span-2 relative">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                           placeholder="Confirm new password">
                    <button type="button" class="absolute top-10 right-3 text-sm text-gray-600 dark:text-gray-300 toggle-password" data-target="#password_confirmation">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="eye-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
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
