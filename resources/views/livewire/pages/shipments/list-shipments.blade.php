<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Shipments') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Track and manage shipments') }}</p>
    </div>
    {{ $this->table }}
    <x-filament-actions::modals />
</div>
