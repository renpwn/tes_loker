<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
        
        <style>
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_paginate.paging_simple_numbers {
                color: white !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current{
                border: 1px solid #ffffffff !important;
            }
            
            .dataTables_wrapper .dataTables_length select {
                /* Hapus panah default browser */
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;

                /* Tambahkan padding kanan yang lebar untuk memberi ruang pada ikon kustom */
                padding-right: 2rem !important; 
                
                /* Pastikan border-radius, background, dan border sudah konsisten */
                background-color: #1f2937;
                border: 1px solid #9ca3af;
                color: inherit;
                border-radius: 0.375rem; 
            }
        </style>
    </body>
</html>
