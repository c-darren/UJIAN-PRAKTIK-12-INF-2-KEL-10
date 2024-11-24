@extends('dashboard.components.layout')
@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="flex flex-col items-center py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Edit Profile {{ old('name', $user->name) }}</h2>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-update-form" class="w-full">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Full Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                    <input  type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter full name">
                </div>

                <!-- Username -->
                <div class="sm:col-span-2">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter username">
                    <small class="text-gray-500 dark:text-gray-400">Username must be unique.</small>
                </div>

                <!-- Email -->
                <div class="sm:col-span-2">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter email address">
                </div>

                <!-- Avatar -->
                <div class="sm:col-span-2">
                    <label for="avatar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Avatar (Optional)</label>
                    <div class="flex items-center">
                        <input type="file" accept="image/jpeg, image/png, image/gif" name="avatar" id="avatar" class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none" onchange="previewAvatar(event)">
                        <button type="button" onclick="openModal()" class="ml-4 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">View Avatar</button>
                    </div>
                    <small class="text-gray-500 dark:text-gray-400">Upload a profile picture if desired. Max file size: 5MB</small>
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit" class="rounded-full inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-300 focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900">Save Changes</button>
            </div>
        </form>

        <!-- Modal for viewing avatars -->
        <div id="avatarModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Avatar Preview</h3>
                        <div class="mt-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current Avatar:</p>
                            @if ($user->avatar)
                                {{-- <img src="{{ Storage::url(($user->avatar ?? 'no_image.png')) }}" alt="Current Avatar" class="w-32 h-32 rounded-full mx-auto"> --}}
                                <img src="{{ Storage::url( ($user->avatar ?? 'avatars/no_image.png')) }}" alt="Current Avatar" class="w-32 h-32 rounded-full mx-auto">
                            @else
                                <p class="text-center text-gray-500 dark:text-gray-400">No avatar uploaded.</p>
                            @endif
                        </div>
                        <div class="mt-5 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">New Avatar:</p>
                            <img id="newAvatarPreview" src="#" alt="New Avatar Preview" class="w-32 h-32 rounded-full mx-auto hidden">
                            <p id="noNewAvatar" class="text-center text-gray-500 dark:text-gray-400">No new avatar selected.</p>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button onclick="closeModal()" id="submit-button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of modal -->
    </div>
</section>
@endsection

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/profile/view_avatar.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/profile/update_profile.js') }}"></script>
@endsection
