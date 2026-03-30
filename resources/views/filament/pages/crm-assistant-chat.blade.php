<x-filament-panels::page>
    <div class="flex h-[calc(100vh-10rem)] rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-900">

        {{-- Left Sidebar - Conversation History (macOS style) --}}
        <div class="w-72 shrink-0 flex flex-col border-e border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-xl">

            {{-- Sidebar Header --}}
            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                <button
                    wire:click="newConversation"
                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-primary-500 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-600 active:bg-primary-700"
                >
                    <x-filament::icon
                        :icon="\Filament\Support\Icons\Heroicon::Plus"
                        class="w-4 h-4"
                    />
                    {{ __('New Chat') }}
                </button>
            </div>

            {{-- Conversation List --}}
            <div class="flex-1 overflow-y-auto">
                @forelse ($this->conversations as $conversation)
                    <button
                        wire:click="loadConversation('{{ $conversation->id }}')"
                        @class([
                            'w-full text-left px-3 py-2.5 border-b border-gray-100 dark:border-gray-800 transition group relative',
                            'bg-primary-50 dark:bg-primary-900/20 border-l-2 border-l-primary-500' => $conversationId === $conversation->id,
                            'hover:bg-gray-100 dark:hover:bg-gray-800/60 border-l-2 border-l-transparent' => $conversationId !== $conversation->id,
                        ])
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p @class([
                                    'text-sm truncate',
                                    'font-semibold text-primary-700 dark:text-primary-400' => $conversationId === $conversation->id,
                                    'font-medium text-gray-900 dark:text-gray-100' => $conversationId !== $conversation->id,
                                ])>
                                    {{ $conversation->title ?: __('Untitled') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($conversation->updated_at)->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Delete button --}}
                            <button
                                wire:click.stop="deleteConversation('{{ $conversation->id }}')"
                                wire:confirm="{{ __('Are you sure you want to delete this conversation?') }}"
                                class="opacity-0 group-hover:opacity-100 shrink-0 p-1 rounded-md text-gray-400 hover:text-danger-500 hover:bg-danger-50 dark:hover:bg-danger-900/20 transition"
                            >
                                <x-filament::icon
                                    :icon="\Filament\Support\Icons\Heroicon::Trash"
                                    class="w-3.5 h-3.5"
                                />
                            </button>
                        </div>
                    </button>
                @empty
                    <div class="p-4 text-center text-sm text-gray-400 dark:text-gray-500">
                        {{ __('No conversations yet') }}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Right Panel - Chat Area --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Chat Messages --}}
            <div
                class="flex-1 overflow-y-auto p-6 space-y-4"
                x-data
                x-ref="chatContainer"
                x-effect="$nextTick(() => $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight)"
            >
                @forelse ($chatHistory as $entry)
                    <div @class([
                        'flex',
                        'justify-end' => $entry['role'] === 'user',
                        'justify-start' => $entry['role'] === 'assistant',
                    ])>
                        @if ($entry['role'] === 'assistant')
                            <div class="flex items-start gap-3 max-w-[85%] min-w-0">
                                <div class="shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center mt-0.5">
                                    <x-filament::icon
                                        :icon="\Filament\Support\Icons\Heroicon::Sparkles"
                                        class="w-4 h-4 text-primary-600 dark:text-primary-400"
                                    />
                                </div>
                                <div class="min-w-0 rounded-2xl rounded-tl-md bg-gray-100 dark:bg-gray-800 px-4 py-3 overflow-hidden">
                                    <div class="prose dark:prose-invert prose-sm max-w-none overflow-x-auto wrap-break-word [&_table]:text-xs [&_pre]:overflow-x-auto">
                                        {!! \Illuminate\Support\Str::markdown($entry['content']) !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="max-w-[75%] rounded-2xl rounded-tr-md bg-primary-500 px-4 py-3 text-sm text-white shadow-sm wrap-break-word">
                                {{ $entry['content'] }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center space-y-3 text-gray-400 dark:text-gray-500 max-w-sm">
                            <div class="mx-auto w-16 h-16 rounded-2xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center">
                                <x-filament::icon
                                    :icon="\Filament\Support\Icons\Heroicon::OutlinedChatBubbleLeftRight"
                                    class="w-8 h-8 text-primary-400"
                                />
                            </div>
                            <p class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                                {{ __('CRM AI Assistant') }}
                            </p>
                            <p class="text-sm leading-relaxed">
                                {{ __('Ask me about customers, orders, invoices, products, opportunities, and more. I have read-only access to all your CRM data.') }}
                            </p>
                        </div>
                    </div>
                @endforelse

                {{-- Loading indicator --}}
                @if ($isLoading)
                    <div class="flex justify-start">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center mt-0.5">
                                <x-filament::icon
                                    :icon="\Filament\Support\Icons\Heroicon::Sparkles"
                                    class="w-4 h-4 text-primary-600 dark:text-primary-400 animate-pulse"
                                />
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl rounded-tl-md px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Input Area --}}
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-900">
                <form wire:submit="sendMessage" class="flex items-end gap-3">
                    <div class="flex-1">
                        <textarea
                            wire:model="message"
                            rows="2"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm shadow-sm transition duration-75 placeholder:text-gray-400 focus:border-primary-500 focus:bg-white focus:ring-1 focus:ring-inset focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-primary-500 dark:focus:bg-gray-800 resize-none"
                            placeholder="{{ __('Type your message...') }}"
                            @keydown.enter.prevent="if (!event.shiftKey) { $wire.sendMessage(); }"
                            @disabled($isLoading)
                        ></textarea>
                    </div>

                    <x-filament::button
                        type="submit"
                        :disabled="$isLoading"
                        :icon="\Filament\Support\Icons\Heroicon::PaperAirplane"
                        size="lg"
                        class="rounded-xl"
                    >
                        {{ __('Send') }}
                    </x-filament::button>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>
