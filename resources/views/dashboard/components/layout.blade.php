<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script> --}}
    @vite(['resources/css/app.css',
    'resources/js/app.js', ])
    {{-- <link rel="stylesheet" src="https://cdn.datatables.net/2.1.8/css/dataTables.tailwindcss.css">
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/dist/notiflix-aio-3.2.7.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        </style>
    <title>@yield('title')</title>
    @livewireStyles
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
        {{-- Default Scripts --}}
        <script src="{{ asset('js/route/dashboard/effects/fade.js') }}"></script>
        <script src="{{ asset('js/default/notiflix_init.js') }}"></script>
        {{-- <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script> --}}
        {{-- <script type="text/javascript" src="{{ asset('js/websocket/websocket.js') }}"></script> --}}
        @yield('required_scripts')
        {{-- @livewireScriptConfig --}}
        @livewireScripts
    </body>
</html>