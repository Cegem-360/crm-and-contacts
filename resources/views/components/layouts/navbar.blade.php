<nav class="bg-white border-b border-gray-100 fixed w-full top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Left: Logo + Module Name --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}"
                         alt="{{ config('app.name') }}" class="h-10">
                    <span class="text-sm font-semibold text-red-600">
                        Értékesítés
                    </span>
                </a>
            </div>

            {{-- Center: Navigation Links (desktop) --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="#funkciok" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    Funkciók
                </a>
                <a href="#integraciok" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    Integrációk
                </a>
                <a href="#arak" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    Árak
                </a>
                <a href="#gyik" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    GYIK
                </a>
            </div>

            {{-- Right: Auth + CTA --}}
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                    Bejelentkezés
                </a>
                <a href="#arak" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors">
                    Ingyenes próba
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>

            {{-- Mobile menu button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
                <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu (collapsible) --}}
    <div x-show="mobileMenuOpen" x-collapse class="lg:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="#funkciok" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                Funkciók
            </a>
            <a href="#integraciok" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                Integrációk
            </a>
            <a href="#arak" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                Árak
            </a>
            <a href="#gyik" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                GYIK
            </a>
            <div class="border-t border-gray-200 pt-3 mt-3">
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                    Bejelentkezés
                </a>
                <a href="#arak" @click="mobileMenuOpen = false" class="block mt-2 px-5 py-2.5 text-center text-sm font-medium text-white bg-red-600 rounded-full hover:bg-red-700">
                    Ingyenes próba
                </a>
            </div>
        </div>
    </div>
</nav>
