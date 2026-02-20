<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <script>
        // Badge style used for Blade syntax placeholders
        window._bladeBadgeStyle = 'background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:3px;font-size:11px;font-family:monospace;';

        // Read-only preview processor for Split mode (strips directives, no round-trip needed)
        window.htmlCodeEditorProcessPreview = function(html) {
            if (!html) return '';
            var out = html;
            var bb = String.fromCharCode(123, 123);
            var be = String.fromCharCode(125, 125);
            var at = String.fromCharCode(64);
            var s = window._bladeBadgeStyle;
            out = out.replace(new RegExp(bb + '\\s*(.+?)\\s*' + be, 'g'), function(m, expr) {
                var label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="' + s + '">' + label + '</span>';
            });
            out = out.replace(/\{!!\s*(.+?)\s*!!\}/g, function(m, expr) {
                var label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="' + s + '">' + label + '</span>';
            });
            var dp = new RegExp('^[ \\t]*' + at + '(foreach|endforeach|for|endfor|while|endwhile|if|endif|else|elseif|unless|endunless|isset|endisset|empty|endempty|switch|endswitch|case|break|default|php|endphp|section|endsection|yield|extends|include|push|endpush|once|endonce)\\b.*$', 'gm');
            out = out.replace(dp, '');
            out = out.replace(/\$([a-zA-Z_]\w*(?:->[\w()]+|(?:\[[^\]]+\]))*)/g, function(m) {
                var label = m.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return '<span style="' + s + '">' + label + '</span>';
            });
            return out;
        };

        // Reversible converter for Visual mode: Blade to badges with base64 data-b attribute
        window.htmlCodeEditorToVisual = function(html) {
            if (!html) return '';
            var out = html;
            var bb = String.fromCharCode(123, 123);
            var be = String.fromCharCode(125, 125);
            var at = String.fromCharCode(64);
            var s = window._bladeBadgeStyle;

            function toBadge(original, label) {
                var encoded = btoa(unescape(encodeURIComponent(original)));
                return '<span data-b="' + encoded + '" contenteditable="false" style="' + s + '">' + label + '</span>';
            }

            // Replace Blade echo tags
            out = out.replace(new RegExp(bb + '\\s*(.+?)\\s*' + be, 'g'), function(m, expr) {
                var label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return toBadge(m, label);
            });
            // Replace unescaped Blade echo tags
            out = out.replace(/\{!!\s*(.+?)\s*!!\}/g, function(m, expr) {
                var label = expr.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return toBadge(m, label);
            });
            // Replace Blade directives with badges (not stripping them)
            var dp = new RegExp('(^[ \\t]*)(' + at + '(?:foreach|endforeach|for|endfor|while|endwhile|if|endif|else|elseif|unless|endunless|isset|endisset|empty|endempty|switch|endswitch|case|break|default|php|endphp|section|endsection|yield|extends|include|push|endpush|once|endonce)\\b[^\\n]*)', 'gm');
            out = out.replace(dp, function(m, indent, directive) {
                var label = directive.trim();
                return indent + toBadge(directive, label);
            });
            // Replace bare PHP variables
            out = out.replace(/\$([a-zA-Z_]\w*(?:->[\w()]+|(?:\[[^\]]+\]))*)/g, function(m) {
                var label = m.replace(/^\$/, '').replace(/->/g, '.').replace(/[()]/g, '');
                return toBadge(m, label);
            });
            return out;
        };

        // Reverse converter: badges back to original Blade syntax
        window.htmlCodeEditorFromVisual = function(html) {
            if (!html) return '';
            return html.replace(/<span[^>]*\bdata-b="([^"]*)"[^>]*>.*?<\/span>/gi, function(m, encoded) {
                try {
                    return decodeURIComponent(escape(atob(encoded)));
                } catch(e) {
                    return m;
                }
            });
        };
    </script>

    <div
        x-data="{
            state: $wire.$entangle(@js($getStatePath())),
            viewMode: 'html',
            showVars: false,
            insertVar(expr) {
                const bb = String.fromCharCode(123, 123);
                const be = String.fromCharCode(125, 125);
                const tag = bb + ' ' + expr + ' ' + be;
                if (this.viewMode === 'visual') {
                    document.execCommand('insertHTML', false, tag);
                    this.syncFromVisual();
                } else {
                    this.insert(tag);
                }
                this.showVars = false;
            },
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
                if (this.viewMode !== 'split' || !this.$refs.preview) return;
                const doc = this.$refs.preview.contentDocument;
                doc.open();
                doc.write(window.htmlCodeEditorProcessPreview(this.state || ''));
                doc.close();
            },
            loadVisualEditor() {
                if (!this.$refs.visualEditor) return;
                this.$refs.visualEditor.innerHTML = window.htmlCodeEditorToVisual(this.state || '');
            },
            syncFromVisual() {
                if (!this.$refs.visualEditor) return;
                this.state = window.htmlCodeEditorFromVisual(this.$refs.visualEditor.innerHTML);
            },
            visualExec(command, value = null) {
                document.execCommand(command, false, value);
                this.$refs.visualEditor.focus();
                this.syncFromVisual();
            },
            setMode(mode) {
                this.viewMode = mode;
                if (mode === 'visual') {
                    this.$nextTick(() => this.loadVisualEditor());
                } else if (mode === 'split') {
                    this.$nextTick(() => this.updatePreview());
                }
            }
        }"
        x-effect="if (viewMode === 'split') { $nextTick(() => updatePreview()) }"
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

            {{-- Variables reference panel --}}
            <div class="relative border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                <button type="button" @click="showVars = !showVars"
                    :class="showVars ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-500 dark:text-gray-400'"
                    class="px-2.5 py-1 text-xs font-medium rounded hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                    <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    {{ __('Variables') }}
                </button>
                <div x-show="showVars" x-cloak @click.outside="showVars = false"
                    class="absolute left-0 top-full mt-1 z-50 w-80 max-h-96 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-3 text-xs">

                    <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1.5">$quote</p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(['$quote->quote_number', '$quote->issue_date', '$quote->valid_until', '$quote->status', '$quote->subtotal', '$quote->discount_amount', '$quote->tax_amount', '$quote->total', '$quote->notes'] as $var)
                            <button type="button" @click="insertVar('{{ $var }}')"
                                class="px-1.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded hover:bg-blue-100 dark:hover:bg-blue-900/50 font-mono transition">{{ str_replace(['$quote->', '$customer->', '$item->'], '', $var) }}</button>
                        @endforeach
                    </div>

                    <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1.5">$customer</p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(['$customer->name', '$customer->email', '$customer->phone', '$customer->type', '$customer->tax_number', '$customer->registration_number', '$customer->eu_tax_number', '$customer->industry', '$customer->website'] as $var)
                            <button type="button" @click="insertVar('{{ $var }}')"
                                class="px-1.5 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded hover:bg-green-100 dark:hover:bg-green-900/50 font-mono transition">{{ str_replace('$customer->', '', $var) }}</button>
                        @endforeach
                    </div>

                    <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1.5">$item <span class="font-normal text-gray-400">(@@foreach)</span></p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(['$item->description', '$item->quantity', '$item->unit_price', '$item->discount_percent', '$item->discount_amount', '$item->tax_rate', '$item->total'] as $var)
                            <button type="button" @click="insertVar('{{ $var }}')"
                                class="px-1.5 py-0.5 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded hover:bg-amber-100 dark:hover:bg-amber-900/50 font-mono transition">{{ str_replace('$item->', '', $var) }}</button>
                        @endforeach
                    </div>

                    <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1.5">$item->product</p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(['$item->product->name', '$item->product->sku', '$item->product->description'] as $var)
                            <button type="button" @click="insertVar('{{ $var }}')"
                                class="px-1.5 py-0.5 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded hover:bg-orange-100 dark:hover:bg-orange-900/50 font-mono transition">{{ str_replace('$item->product->', '', $var) }}</button>
                        @endforeach
                    </div>

                    <p class="font-semibold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('Helpers') }}</p>
                    <div class="flex flex-wrap gap-1">
                        <button type="button" @click="insertVar('config(\'app.name\')')"
                            class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 font-mono transition">app.name</button>
                        <button type="button" @click="insertVar('now()->format(\'Y-m-d\')')"
                            class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 font-mono transition">now()</button>
                    </div>
                </div>
            </div>

            {{-- HTML mode toolbar: tag insertion buttons --}}
            <template x-if="viewMode === 'html' || viewMode === 'split'">
                <div class="flex flex-wrap gap-1">
                    {{-- Headings --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="insert('<h1>', '</h1>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 1">H1</button>
                        <button type="button" @click="insert('<h2>', '</h2>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 2">H2</button>
                        <button type="button" @click="insert('<h3>', '</h3>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 3">H3</button>
                    </div>

                    {{-- Text formatting --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="insert('<p>', '</p>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Paragraph">P</button>
                        <button type="button" @click="insert('<strong>', '</strong>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Bold">B</button>
                        <button type="button" @click="insert('<em>', '</em>')" class="px-2 py-1 text-xs italic rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Italic">I</button>
                        <button type="button" @click="insert('<u>', '</u>')" class="px-2 py-1 text-xs underline rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Underline">U</button>
                    </div>

                    {{-- Lists --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="insertBlock('<ul>\n    <li></li>\n</ul>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Unordered list">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </button>
                        <button type="button" @click="insertBlock('<ol>\n    <li></li>\n</ol>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Ordered list">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    {{-- Structure --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="insertBlock('<table class=&quot;items&quot;>\n    <thead>\n        <tr>\n            <th></th>\n        </tr>\n    </thead>\n    <tbody>\n        <tr>\n            <td></td>\n        </tr>\n    </tbody>\n</table>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Table">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M14 3v18M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6z"/></svg>
                        </button>
                        <button type="button" @click="insert('<div class=&quot;&quot;>', '</div>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Div">DIV</button>
                        <button type="button" @click="insertBlock('<hr>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Horizontal rule">HR</button>
                        <button type="button" @click="insertBlock('<br>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Line break">BR</button>
                    </div>

                    {{-- Blade snippets --}}
                    <div class="flex gap-0.5">
                        <button type="button" x-on:click="insert('@{{' + ' ', ' ' + '}}')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="Blade echo">@{{ }}</button>
                        <button type="button" x-on:click="insertBlock(String.fromCharCode(64) + 'foreach($items as $item)\n\n' + String.fromCharCode(64) + 'endforeach')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="@@foreach">@@for</button>
                        <button type="button" x-on:click="insertBlock(String.fromCharCode(64) + 'if()\n\n' + String.fromCharCode(64) + 'endif')" class="px-1.5 py-1 text-xs rounded hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-mono" title="@@if">@@if</button>
                    </div>
                </div>
            </template>

            {{-- Visual mode toolbar: WYSIWYG formatting --}}
            <template x-if="viewMode === 'visual'">
                <div class="flex flex-wrap gap-1">
                    {{-- Text formatting --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="visualExec('bold')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Bold">B</button>
                        <button type="button" @click="visualExec('italic')" class="px-2 py-1 text-xs italic rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Italic">I</button>
                        <button type="button" @click="visualExec('underline')" class="px-2 py-1 text-xs underline rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Underline">U</button>
                        <button type="button" @click="visualExec('strikeThrough')" class="px-2 py-1 text-xs line-through rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Strikethrough">S</button>
                    </div>

                    {{-- Headings & blocks --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="visualExec('formatBlock', '<h1>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 1">H1</button>
                        <button type="button" @click="visualExec('formatBlock', '<h2>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 2">H2</button>
                        <button type="button" @click="visualExec('formatBlock', '<h3>')" class="px-2 py-1 text-xs font-bold rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Heading 3">H3</button>
                        <button type="button" @click="visualExec('formatBlock', '<p>')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Paragraph">P</button>
                    </div>

                    {{-- Lists --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="visualExec('insertUnorderedList')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Bullet list">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </button>
                        <button type="button" @click="visualExec('insertOrderedList')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Numbered list">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1zm0 6a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    {{-- Alignment --}}
                    <div class="flex gap-0.5 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                        <button type="button" @click="visualExec('justifyLeft')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Align left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h12M3 18h18"/></svg>
                        </button>
                        <button type="button" @click="visualExec('justifyCenter')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Align center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M6 12h12M3 18h18"/></svg>
                        </button>
                        <button type="button" @click="visualExec('justifyRight')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Align right">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M9 12h12M3 18h18"/></svg>
                        </button>
                    </div>

                    {{-- Undo/Redo --}}
                    <div class="flex gap-0.5">
                        <button type="button" @click="visualExec('undo')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Undo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 010 8H9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6L3 10l4 4"/></svg>
                        </button>
                        <button type="button" @click="visualExec('redo')" class="px-2 py-1 text-xs rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" title="Redo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a4 4 0 000 8h4"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 6l4 4-4 4"/></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Editor + Preview area --}}
        <div class="flex border border-t-0 border-gray-200 dark:border-gray-700 rounded-b-lg overflow-hidden"
            :class="viewMode === 'split' ? 'divide-x divide-gray-200 dark:divide-gray-700' : ''">

            {{-- Code editor (HTML & Split modes) --}}
            <div x-show="viewMode !== 'visual'"
                :class="viewMode === 'split' ? 'w-1/2' : 'w-full'">
                <textarea
                    x-ref="editor"
                    x-model="state"
                    x-on:input="if (viewMode === 'split') updatePreview()"
                    rows="{{ $getRows() }}"
                    class="w-full h-full font-mono text-sm p-4 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-0 focus:ring-0 resize-y"
                    style="tab-size: 4; min-height: 400px;"
                    @if($isDisabled()) disabled @endif
                ></textarea>
            </div>

            {{-- Visual WYSIWYG editor (Visual mode) --}}
            <div x-show="viewMode === 'visual'" x-cloak class="w-full bg-white" style="min-height: 400px;">
                <div
                    x-ref="visualEditor"
                    contenteditable="true"
                    @input="syncFromVisual()"
                    class="w-full min-h-[400px] p-4 text-sm text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-900 focus:outline-none prose prose-sm max-w-none dark:prose-invert"
                    style="min-height: 400px;"
                ></div>
                <div class="px-3 py-1.5 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-200 dark:border-amber-800 text-xs text-amber-600 dark:text-amber-400">
                    {{ __('Visual mode edits HTML directly. Blade syntax shown as badges is preserved.') }}
                </div>
            </div>

            {{-- Split preview (Split mode) --}}
            <div x-show="viewMode === 'split'" x-cloak class="w-1/2 bg-white" style="min-height: 400px;">
                <div class="sticky top-0 px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ __('Preview') }}
                </div>
                <iframe x-ref="preview" class="w-full border-0" style="min-height: 380px; height: calc(100% - 30px);" sandbox="allow-same-origin"></iframe>
            </div>
        </div>

    </div>
</x-dynamic-component>
