{{-- Sales module sidebar - 240px width with red accent --}}
<aside
    class="fixed inset-y-0 left-0 z-50 w-60 bg-[#292F4C] text-white flex flex-col transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
    :class="{
        'lg:-translate-x-full': !sidebarOpen,
        'lg:translate-x-0': sidebarOpen,
        '-translate-x-full': !mobileMenuOpen,
        'translate-x-0': mobileMenuOpen
    }">
    {{-- Logo area --}}
    <div class="h-16 flex items-center px-4 border-b border-white/10">
        <a href="{{ route('dashboard', ['team' => $currentTeam]) }}" class="flex items-center gap-2">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}"
                class="h-8 brightness-0 invert">
            <span class="text-sm font-semibold text-red-400">{{ __('Sales') }}</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
        <x-sidebar-section title="Navigation">
            <x-sidebar-item route="dashboard"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                active-pattern="dashboard" active-exclude="dashboard.*">
                {{ __('Home') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.sales-dashboard" icon-color="text-emerald-400"
                icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                {{ __('Sales Dashboard') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Customers">
            <x-sidebar-item route="dashboard.customers" icon-color="text-red-400"
                icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                {{ __('Customers') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.opportunities" icon-color="text-red-400"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                {{ __('Leads') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Sales">
            <x-sidebar-item route="dashboard.quotes" icon-color="text-blue-400"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                {{ __('Quotes') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.orders" icon-color="text-blue-400"
                icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                {{ __('Orders') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.invoices" icon-color="text-blue-400"
                icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                {{ __('Invoices') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.shipments" icon-color="text-blue-400" icon="M8 16l-4-4m0 0l4-4m-4 4h18">
                {{ __('Shipments') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Products">
            <x-sidebar-item route="dashboard.products" icon-color="text-orange-400"
                icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                {{ __('Products') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.product-categories" icon-color="text-orange-400"
                icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                {{ __('Product Categories') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.discounts" icon-color="text-orange-400"
                icon="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                {{ __('Discounts') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Marketing">
            <x-sidebar-item route="dashboard.campaigns" icon-color="text-purple-400"
                icon="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                {{ __('Campaigns') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Activities">
            <x-sidebar-item route="dashboard.tasks" icon-color="text-cyan-400"
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                {{ __('Tasks') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.interactions" icon-color="text-cyan-400"
                icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                {{ __('Interactions') }}
            </x-sidebar-item>
            <x-sidebar-item route="dashboard.chat-sessions" icon-color="text-cyan-400"
                icon="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                {{ __('Chat Sessions') }}
            </x-sidebar-item>
        </x-sidebar-section>

        <x-sidebar-section title="Support">
            <x-sidebar-item route="dashboard.complaints" icon-color="text-gray-400"
                icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                {{ __('Complaints') }}
            </x-sidebar-item>
        </x-sidebar-section>
    </nav>

    {{-- User section at bottom --}}
    @auth
        <div class="border-t border-white/10 p-4">
            <a href="{{ route('filament.admin.auth.profile') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="truncate font-medium">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </a>
        </div>
    @endauth
</aside>
