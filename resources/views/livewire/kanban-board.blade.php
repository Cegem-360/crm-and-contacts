<div>
    <x-breadcrumb :items="[
        ['label' => __('Leads / Opportunities'), 'url' => route('dashboard.opportunities', ['team' => $currentTeam])],
        ['label' => __('Kanban Board')],
    ]" />

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Pipeline Kanban') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Drag opportunities between stages') }}</p>
        </div>
        <a href="{{ route('dashboard.opportunities', ['team' => $currentTeam]) }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            {{ __('List View') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-wrap items-end gap-4 rounded-xl bg-white dark:bg-gray-800 p-4 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
        <div class="min-w-[160px]">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Assignee') }}</label>
            <select wire:model.live="filterAssignee" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                <option value="">{{ __('All') }}</option>
                @foreach($teamUsers as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Period') }}</label>
            <select wire:model.live="filterPeriod" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                <option value="">{{ __('All time') }}</option>
                <option value="1">{{ __('Last 1 month') }}</option>
                <option value="3">{{ __('Last 3 months') }}</option>
                <option value="6">{{ __('Last 6 months') }}</option>
                <option value="12">{{ __('Last 12 months') }}</option>
            </select>
        </div>
        <div class="min-w-[120px]">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Min Value') }}</label>
            <input type="number" wire:model.live.debounce.500ms="filterMinValue" placeholder="0"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
        </div>
        <div class="min-w-[120px]">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Max Value') }}</label>
            <input type="number" wire:model.live.debounce.500ms="filterMaxValue" placeholder="∞"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
        </div>
    </div>

    {{-- Kanban Board --}}
    <div
        class="flex gap-4 overflow-x-auto pb-4"
        x-data="kanbanBoard()"
        x-init="initSortable()"
    >
        @foreach($stages as $stageValue => $stage)
            <div class="shrink-0 w-72">
                {{-- Stage Header --}}
                <div class="mb-3 rounded-xl bg-white dark:bg-gray-800 p-3 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full
                                @switch($stage['color'])
                                    @case('gray') bg-gray-400 @break
                                    @case('info') bg-sky-400 @break
                                    @case('warning') bg-amber-400 @break
                                    @case('primary') bg-blue-500 @break
                                    @case('success') bg-emerald-500 @break
                                    @case('danger') bg-red-500 @break
                                    @default bg-gray-400
                                @endswitch
                            "></span>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stage['label'] }}</h3>
                        </div>
                        <span class="inline-flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300">
                            {{ $stage['count'] }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ number_format($stage['total'], 0, ',', '.') }} Ft
                    </p>
                </div>

                {{-- Cards Container --}}
                <div
                    class="kanban-column space-y-3 min-h-[200px] rounded-xl bg-gray-100/50 dark:bg-gray-800/30 p-2"
                    data-stage="{{ $stageValue }}"
                >
                    @foreach($stage['opportunities'] as $opp)
                        <div
                            class="kanban-card cursor-grab rounded-lg bg-white dark:bg-gray-800 p-3 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-md transition-shadow"
                            data-id="{{ $opp['id'] }}"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $opp['customer_name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $opp['title'] }}</p>
                                </div>
                                <div class="shrink-0 w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold"
                                    title="{{ $opp['assignee_name'] }}">
                                    {{ $opp['assignee_initials'] }}
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ number_format($opp['value'], 0, ',', '.') }} Ft
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $opp['probability'] >= 70 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                       ($opp['probability'] >= 40 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                                       'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ $opp['probability'] }}%
                                </span>
                            </div>
                            @if($opp['expected_close_date'])
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                    {{ __('Close') }}: {{ $opp['expected_close_date'] }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@script
<script>
    Alpine.data('kanbanBoard', () => ({
        initSortable() {
            import('https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/+esm').then((module) => {
                const Sortable = module.default;
                const columns = document.querySelectorAll('.kanban-column');

                columns.forEach(column => {
                    new Sortable(column, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-30',
                        dragClass: 'shadow-xl',
                        handle: '.kanban-card',
                        onEnd: (evt) => {
                            const cardId = parseInt(evt.item.dataset.id);
                            const newStage = evt.to.dataset.stage;

                            if (evt.from.dataset.stage !== newStage) {
                                $wire.moveOpportunity(cardId, newStage);
                            }
                        }
                    });
                });
            });
        }
    }));
</script>
@endscript
