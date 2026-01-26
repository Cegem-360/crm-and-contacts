<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Left side - Form -->
        <div class="flex w-full flex-col bg-white lg:w-1/2">
            <!-- Logo header -->
            <div class="flex items-center justify-between px-6 py-6 lg:px-12">
                <a href="{{ route('home') }}">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10">
                </a>
                <x-language-switcher />
            </div>
            <!-- Main content area - centered -->
            <div class="flex flex-1 flex-col items-center justify-center px-6 pb-6 lg:px-12">
                <div class="w-full max-w-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Right side - Illustration with floating elements -->
        <div class="hidden bg-red-600 lg:flex lg:w-1/2 lg:items-center lg:justify-center relative overflow-hidden">
            <!-- Concentric circles -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[800px] h-[800px] border-2 border-white/20 rounded-full"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[600px] h-[600px] border-2 border-white/25 rounded-full"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[400px] h-[400px] border-2 border-white/20 rounded-full"></div>
            </div>

            <div class="relative w-full max-w-2xl px-12">
                <!-- Dashboard mockup card - Sales module specific -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-6 relative z-10">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('Sales') }}</p>
                                <p class="text-xs text-gray-500">{{ __('Quote management') }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-red-600 bg-red-50 rounded-full">{{ __('Professional') }}</span>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-gray-900">127</p>
                            <p class="text-xs text-gray-500">{{ __('Quotes this month') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-green-600">+34%</p>
                            <p class="text-xs text-gray-500">{{ __('Quote conversion') }}</p>
                        </div>
                    </div>

                    <!-- Recent quote -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">AJ-2024-0127</p>
                                <p class="text-xs text-gray-500">Tech Solutions Kft.</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium text-amber-600 bg-amber-50 rounded-full">{{ __('Pending') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Floating notification -->
                <div class="absolute -left-8 top-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100 animate-pulse z-20">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-900">{{ __('Quote accepted') }}</p>
                            <p class="text-xs text-gray-500">AJ-2024-0125</p>
                        </div>
                    </div>
                </div>

                <!-- Floating stat -->
                <div class="absolute -right-4 bottom-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100 z-20">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-900">3.2M Ft</p>
                            <p class="text-xs text-gray-500">{{ __('Revenue today') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-16 right-16 w-24 h-24 border-2 border-white/30 rounded-full"></div>
            <div class="absolute bottom-24 left-12 w-16 h-16 bg-red-400/40 rounded-full"></div>
        </div>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
