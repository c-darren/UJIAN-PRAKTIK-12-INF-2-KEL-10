@extends('auth.components.layout')

@section('title', 'Sign Up')

@section('content')

<section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto overflow-y-auto">
        <a href="{{ route('login') }}" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            <img class="w-8 h-8 mr-2" src={{ Storage::url('website/logo.png') }} alt="ECOCASS logo">
            ECOCASS
        </a>
        <div class="w-full max-w-6xl bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700" id="signupFormView">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                <!-- Signup Form -->
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-center">
                    Sign Up for a New Account
                </h1>

                <form method="POST" action="{{ route('register.submit') }}" id="signupForm" class="space-y-4 md:space-y-6" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Section 1: Personal Information -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Personal Information</h2>

                            <!-- Full Name -->
                            <div>
                                <label for="name" class=" block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter your full name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 
                                    dark:focus:border-primary-500">
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                <input type="text" name="username" id="username" placeholder="Enter your username"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 
                                    dark:focus:border-primary-500">
                                <small class="text-gray-500 dark:text-gray-400">Username must be unique.</small>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="sm:col-span-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input type="text" name="email" id="email" placeholder="Enter your email address"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 
                                    dark:focus:border-primary-500">
                            </div>

                            <!-- Avatar -->
                            <div>
                                <label for="avatar" class="sm:col-span-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Avatar (Optional)</label>
                                <input type="file" accept="image/jpeg, image/png, image/gif" name="avatar" id="avatar"
                                    class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 
                                    dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                                <small class="text-gray-500 dark:text-gray-400">Upload a profile picture if desired. Max file size: 5MB</small>
                                <!-- Avatar Preview -->
                                <div class="mt-2">
                                    <img id="avatarPreview" src="" alt="Avatar Preview" class="w-24 h-24 rounded-full object-cover cursor-pointer hidden">
                                    <!-- Button to view avatar in larger size -->
                                    <button type="button" id="viewAvatarButton" class="mt-2 text-primary-600 hover:underline dark:text-primary-500 hidden">View Avatar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Security -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Security</h2>

                            <!-- Password -->
                            <div class="relative">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <input type="password" name="password" id="password" placeholder="Enter your password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 
                                    dark:focus:border-primary-500">
                                <button type="button" class="absolute top-10 right-3 text-sm text-gray-600 dark:text-gray-300 toggle-password" data-target="#password">
                                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIconPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path id="eyePathPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Confirm Password -->
                            <div class="relative">
                                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 
                                    dark:focus:border-primary-500">
                                <button type="button" class="absolute top-10 right-3 text-sm text-gray-600 dark:text-gray-300 toggle-password" data-target="#password_confirmation">
                                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIconConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path id="eyePathConfirm" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submit" 
                            class="w-full mt-6 text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 
                            focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 
                            text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Sign Up
                    </button>

                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="{{ route('login') }}" 
                        class="font-medium text-primary-600 hover:underline dark:text-primary-500">Log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth/signup.js') }}"></script> 
@endsection
