<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css',
    'resources/js/app.js', ])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <title>{{ config('app.name') }}</title>
    
</head>
    <body class="bg-gray-50 dark:bg-gray-800">
        @include('dashboard.components.header')
        @yield('header')

        <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        
            @include('dashboard.components.sidebar')
            @yield('sidebar')
        
        <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
            <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
                <main>
                    @yield('content')
                </main>

                @include('dashboard.components.footer')
                @yield('footer')
            </div>
        </div>
        @yield('scripts')
    </body>
</html>