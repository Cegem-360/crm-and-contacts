<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Chat Sessions') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('View chat session history') }}</p>
    </div>
    {{ $this->table }}
    <x-filament-actions::modals />
</div>
