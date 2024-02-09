<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Buroflow') }} Local</title>

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

</head>

<body class="h-full font-sans antialiased">
    <x-jet-banner />

    <div x-data="{ open: false }">
        <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
        <x-nav.offcanvas />

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex flex-col flex-grow overflow-y-auto bg-indigo-800">
                <div class="flex items-center flex-shrink-0 px-4 py-2">
                    <a class="block w-full" href="{{ route('dashboard') }}">
                        <div class="flex justify-center shrink-0">
                            <x-jet-application-logo />
                        </div>
                    </a>
                </div>
                <div class="flex flex-col flex-1 mt-5">
                    <x-nav.sidebar />
                </div>
            </div>
        </div>

        <div class="flex flex-col flex-1 md:pl-64">
            <div class="sticky top-0 z-10 flex flex-shrink-0 h-16 bg-white shadow">
                <button type="button" @click="open = ! open"
                    class="px-4 text-gray-500 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <!-- Heroicon name: outline/menu-alt-2 -->
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex justify-between flex-1 px-4">
                    <div class="flex flex-1">
                        {{-- <form class="flex w-full md:ml-0" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/search -->
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="search-field"
                                    class="block w-full h-full py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 border-transparent focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                                    placeholder="Search" type="search" name="search">
                            </div>
                        </form> --}}
                    </div>
                    <div class="flex items-center ml-4 md:ml-6">
                        @livewire('navigation-menu')
                    </div>
                </div>
            </div>

            <main>
                <div class="py-6">
                    <div class="px-4 pb-6 sm:px-6">
                        {{ $header }}
                    </div>
                    <div class="px-4 mx-auto sm:px-6">
                        <!-- Replace with your content -->
                        {{ $slot }}
                        <!-- /End replace -->
                    </div>
                </div>
            </main>
        </div>
    </div>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @stack('modals')
    @filamentScript
    @livewireScripts
    @livewire('notifications')
    <script>
        window.openViewRecordModal = function(recordId) {
            Livewire.emit('openModal', recordId);
        };
    </script>

    @livewire('notifications')
</body>

</html>
