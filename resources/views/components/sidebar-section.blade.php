@props([
    'title',
])

<div>
    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __($title) }}</h3>
    <ul class="space-y-1">
        {{ $slot }}
    </ul>
</div>
