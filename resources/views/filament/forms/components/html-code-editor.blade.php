<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    {{-- Blade-safe preview processor: defined outside x-data to avoid Blade compilation issues --}}
    <script>
        window.htmlCodeEditorProcessPreview = function(html) {
            if (!html) return '';
            let out = html;
            // Replace Blade echo tags with styled placeholder badges
            const dblBraceOpen = String.fromCharCode(123, 123);
            const dblBraceClose = String.fromCharCode(125, 125);
            out = out.replace(new RegExp(dblBraceOpen + '\\s*(.+?)\\s*' + dblBraceClose, 'g'), function(m, expr) {
                const label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:3px;font-size:11px;font-family:monospace;">' + label + '</span>';
            });
            // Replace unescaped Blade echo tags with styled placeholder
            out = out.replace(/\{!!\s*(.+?)\s*!!\}/g, function(m, expr) {
                const label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:3px;font-size:11px;font-family:monospace;">' + label + '</span>';
            });
            // Strip Blade directives but keep inner content
            var at = String.fromCharCode(64);
            var directivePattern = new RegExp('^[ \\t]*' + at + '(foreach|endforeach|for|endfor|while|endwhile|if|endif|else|elseif|unless|endunless|isset|endisset|empty|endempty|switch|endswitch|case|break|default|php|endphp|section|endsection|yield|extends|include|push|endpush|once|endonce)\\b.*$', 'gm');
            out = out.replace(directivePattern, '');
            // Replace bare PHP variables like $var->prop, $var['key'], $var
            out = out.replace(/\$([a-zA-Z_]\w*(?:->[\w()]+|(?:\[[^\]]+\]))*)/g, function(m) {
                var label = m.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:3px;font-size:11px;font-family:monospace;">' + label + '</span>';
            });
            return out;
        };
    </script>

    <div
        x-data="{
            state: $wire.$entangle(@js($getStatePath())),
            viewMode: 'html',
            insert(before, after = '') {
                const ta = this.$refs.editor;
                const start = ta.selectionStart;
                const end = ta.selectionEnd;
                const selected = ta.value.substring(start, end);
                const replacement = before + (selected || '') + after;
                ta.setRangeText(replacement, start, end, 'end');
                this.state = ta.value;
                ta.focus();
            },
            insertBlock(html) {
                const ta = this.$refs.editor;
                const start = ta.selectionStart;
                ta.setRangeText('\n' + html + '\n', start, start, 'end');
                this.state = ta.value;
                ta.focus();
            },
            updatePreview() {
                if (this.viewMode === 'html' || !this.$refs.preview) return;
                const doc = this.$refs.preview.contentDocument;
                doc.open();
                doc.write(window.htmlCodeEditorProcessPreview(this.state || ''));
                doc.close();
            },
            setMode(mode) {
                this.viewMode = mode;
                if (mode !== 'html') {
                    this.$nextTick(() => this.updatePreview());
                }
            }
        }"
        x-effect="if (viewMode !== 'html') { $nextTick(() => updatePreview()) }"
        {{ $getExtraAttributeBag() }}
    >
        {{-- Toolbar --}}
        <div class="flex flex-wrap gap-1 p-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-t-lg">
            {{-- View mode tabs --}}
            <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="setMode('html')"
                    :class="viewMode === 'html' ? 'bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="px-2.5 py-1 text-xs font-medium rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="HTML">
                    <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    HTML
                </button>
                <button type="button" @click="setMode('visual')"
                    :class="viewMode === 'visual' ? 'bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="px-2.5 py-1 text-xs font-medium rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Visual">
                    <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ __('Visual') }}
                </button>
                <button type="button" @click="setMode('split')"
                    :class="viewMode === 'split' ? 'bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="px-2.5 py-1 text-xs font-medium rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Split">
                    <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/></svg>
                    {{ __('Split') }}
                </button>
            </div>

            {{-- Headings (hidden in visual mode) --}}
            <div x-show="viewMode !== 'visual'" class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="insert('<h1>', '</h1>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 1">H1</button>
                <button type="button" @click="insert('<h2>', '</h2>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 2">H2</button>
                <button type="button" @click="insert('<h3>', '</h3>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 3">H3</button>
            </div>

            {{-- Text formatting (hidden in visual mode) --}}
            <div x-show="viewMode !== 'visual'" class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="insert('<p>', '</p>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Paragraph">P</button>
                <button type="button" @click="insert('<strong>', '</strong>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Bold">B</button>
                <button type="button" @click="insert('<em>', '</em>')" class="px-2 py-1 text-xs italic rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Italic">I</button>
                <button type="button" @click="insert('<u>', '</u>')" class="px-2 py-1 text-xs underline rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Underline">U</button>
                <button type="button" @click="insert('<span style=&quot;&quot;>', '</span>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Span with style">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </button>
            </div>

            {{-- Lists (hidden in visual mode) --}}
            <div x-show="viewMode !== 'visual'" class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="insertBlock('<ul>\n    <li></li>\n</ul>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Unordered list">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </button>
                <button type="button" @click="insertBlock('<ol>\n    <li></li>\n</ol>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Ordered list">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </button>
            </div>

            {{-- Structure (hidden in visual mode) --}}
            <div x-show="viewMode !== 'visual'" class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="insertBlock('<table class=&quot;items&quot;>\n    <thead>\n        <tr>\n            <th></th>\n        </tr>\n    </thead>\n    <tbody>\n        <tr>\n            <td></td>\n        </tr>\n    </tbody>\n</table>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Table">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M14 3v18M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6z"/></svg>
                </button>
                <button type="button" @click="insert('<div class=&quot;&quot;>', '</div>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Div">DIV</button>
                <button type="button" @click="insertBlock('<hr>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Horizontal rule">HR</button>
                <button type="button" @click="insertBlock('<br>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Line break">BR</button>
            </div>

            {{-- Blade (hidden in visual mode) --}}
            <div x-show="viewMode !== 'visual'" class="flex gap-0.5">
                <button type="button" x-on:click="insert('@{{' + ' ', ' ' + '}}')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="Blade echo">@{{ }}</button>
                <button type="button" x-on:click="insertBlock(String.fromCharCode(64) + 'foreach($items as $item)\n\n' + String.fromCharCode(64) + 'endforeach')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="@@foreach">@@for</button>
                <button type="button" x-on:click="insertBlock(String.fromCharCode(64) + 'if()\n\n' + String.fromCharCode(64) + 'endif')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="@@if">@@if</button>
            </div>
        </div>

        {{-- Editor + Preview area --}}
        <div class="flex border border-t-0 border-gray-200 dark:border-gray-700 rounded-b-lg overflow-hidden"
            :class="viewMode === 'split' ? 'divide-x divide-gray-200 dark:divide-gray-700' : ''">

            {{-- Code editor --}}
            <div x-show="viewMode !== 'visual'"
                :class="viewMode === 'split' ? 'w-1/2' : 'w-full'">
                <textarea
                    x-ref="editor"
                    x-model="state"
                    x-on:input="if (viewMode !== 'html') updatePreview()"
                    rows="{{ $getRows() }}"
                    class="w-full h-full font-mono text-sm p-4 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-0 focus:ring-0 resize-y"
                    style="tab-size: 4; min-height: 400px;"
                    @if($isDisabled()) disabled @endif
                ></textarea>
            </div>

            {{-- Live preview --}}
            <div x-show="viewMode !== 'html'" x-cloak
                :class="viewMode === 'split' ? 'w-1/2' : 'w-full'"
                class="bg-white" style="min-height: 400px;">
                <div class="sticky top-0 px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ __('Preview') }}
                </div>
                <iframe x-ref="preview" class="w-full border-0" style="min-height: 380px; height: calc(100% - 30px);" sandbox="allow-same-origin"></iframe>
            </div>
        </div>

    </div>
</x-dynamic-component>
