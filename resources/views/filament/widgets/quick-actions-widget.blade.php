<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">{{ __('Quick actions') }}</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ $newOpportunityUrl }}" wire:navigate
                class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <x-heroicon-o-plus class="w-5 h-5 text-red-600 dark:text-red-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('New opportunity') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Create a new opportunity') }}</p>
                </div>
            </a>

            <a href="{{ $newCustomerUrl }}" wire:navigate
                class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <x-heroicon-o-user-plus class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('New customer') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Add a new customer') }}</p>
                </div>
            </a>

            <a href="{{ $opportunitiesUrl }}" wire:navigate
                class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('View opportunities') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('See all opportunities') }}</p>
                </div>
            </a>

            <a href="{{ $customersUrl }}" wire:navigate
                class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <x-heroicon-o-chart-bar class="w-5 h-5 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Customers') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('View all customers') }}</p>
                </div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
