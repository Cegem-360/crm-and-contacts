<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" sizes="any">
        <title>{{ config('app.name') }}</title>

        <!-- Fonts (load first) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|poppins:400,500,600,700" rel="stylesheet" />

        <!-- Vite Assets (CSS + JS) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- CRITICAL: Filament styles provide CSS color variables -->
        @filamentStyles

        <style>
            [x-cloak] {
                display: none !important;
            }
            .font-heading {
                font-family: 'Poppins', sans-serif;
            }
        </style>
        <script>
            // Apply theme before page renders to prevent flash
            (function() {
                const theme = localStorage.getItem('theme') || 'auto';
                const root = document.documentElement;

                if (theme === 'dark') {
                    root.classList.add('dark');
                } else if (theme === 'light') {
                    root.classList.remove('dark');
                } else { // auto
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        root.classList.add('dark');
                    } else {
                        root.classList.remove('dark');
                    }
                }
            })();
        </script>

    </head>

    <body class="antialiased" x-data="{ mobileMenuOpen: false }">
        <x-layouts.navbar />

        {{ $slot }}

        @livewire('notifications')

        @filamentScripts

    </body>

</html>
