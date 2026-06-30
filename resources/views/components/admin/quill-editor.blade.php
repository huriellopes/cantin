@props([
    'placeholder' => 'Escreva o conteúdo...',
    'uploadUrl' => route('admin.editor.attachments.store'),
    'initial' => '',
    'maxUploadKb' => config('editor.max_upload_kb'),
])

@php($modal = 'fixed inset-0 z-[120] flex items-center justify-center p-4')

<div
    wire:ignore
    x-data="quillEditor({
        model: '{{ $attributes->wire('model')->value() }}',
        uploadUrl: '{{ $uploadUrl }}',
        placeholder: '{{ addslashes($placeholder) }}',
        maxUploadKb: {{ (int) $maxUploadKb }},
    })"
    class="group relative"
>
    {{-- Overlay de carregamento durante o upload --}}
    <div x-show="uploading" x-cloak class="absolute inset-0 z-50 flex items-center justify-center rounded-xl bg-white/60 backdrop-blur-[1px]">
        <div class="flex items-center gap-3 rounded-2xl bg-slate-900 px-6 py-3 text-white shadow-2xl">
            <svg class="h-5 w-5 animate-spin text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-bold uppercase tracking-widest" x-text="uploadLabel"></span>
        </div>
    </div>

    {{-- O conteúdo inicial é renderizado AQUI dentro para o Quill adotá-lo na
         construção. Evita setContents pós-init, que corrompia a seleção e
         travava a digitação na edição. --}}
    <div x-ref="editor" class="min-h-[28rem] rounded-b-xl border border-slate-200 bg-white">{!! $initial !!}</div>

    {{-- Modal de vídeo por link (YouTube/Vimeo) ou upload do computador --}}
    <div
        x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail.name === 'quill-video-link') open = true"
        x-on:close-modal.window="if ($event.detail.name === 'quill-video-link') open = false"
        x-show="open"
        x-cloak
        class="{{ $modal }}"
    >
        <div class="absolute inset-0 bg-slate-900/50" @click="open = false"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="mb-4 text-lg font-semibold text-slate-800">Inserir vídeo</h3>
            <div class="space-y-5">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-500">Link do YouTube ou Vimeo</label>
                    <textarea
                        x-model="videoUrl"
                        @keydown.enter.prevent="confirmVideoLink()"
                        rows="3"
                        placeholder="Cole o link (ex.: https://www.youtube.com/watch?v=...) ou o código <iframe>"
                        class="w-full resize-none rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-violet-500 focus:ring-violet-500 focus:outline-none"
                    ></textarea>
                    <p x-show="videoError" x-cloak x-text="videoError" class="mt-2 text-sm text-rose-600"></p>
                    <p class="mt-2 text-[11px] leading-relaxed text-slate-400">Aceita links do YouTube/Vimeo ou o código de incorporação (<code>&lt;iframe&gt;</code>).</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row-reverse">
                    <button type="button" @click="confirmVideoLink()" class="rounded-lg bg-violet-600 px-6 py-2 text-sm font-semibold text-white hover:bg-violet-700">Inserir vídeo</button>
                    <button type="button" @click="uploadVideoFromModal()" class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                        <x-lucide-upload class="mr-2 h-4 w-4" /> Enviar do computador
                    </button>
                    <button type="button" @click="open = false" class="rounded-lg px-6 py-2 text-sm font-medium text-slate-500 hover:text-slate-900">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de link --}}
    <div
        x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail.name === 'quill-link') open = true"
        x-on:close-modal.window="if ($event.detail.name === 'quill-link') open = false"
        x-show="open"
        x-cloak
        class="{{ $modal }}"
    >
        <div class="absolute inset-0 bg-slate-900/50" @click="open = false"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="mb-4 text-lg font-semibold text-slate-800">Inserir link</h3>
            <div class="space-y-5">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-500">URL</label>
                    <input type="url" x-model="linkUrl" @keydown.enter.prevent="confirmLink()" placeholder="https://exemplo.com"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-violet-500 focus:ring-violet-500 focus:outline-none" />
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-500">Texto (opcional)</label>
                    <input type="text" x-model="linkText" @keydown.enter.prevent="confirmLink()" placeholder="Texto a exibir (usado quando nada está selecionado)"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-violet-500 focus:ring-violet-500 focus:outline-none" />
                </div>
                <p x-show="linkError" x-cloak x-text="linkError" class="text-sm text-rose-600"></p>
                <div class="flex flex-col gap-3 sm:flex-row-reverse">
                    <button type="button" @click="confirmLink()" class="rounded-lg bg-violet-600 px-6 py-2 text-sm font-semibold text-white hover:bg-violet-700">Inserir link</button>
                    <button type="button" @click="open = false" class="rounded-lg px-6 py-2 text-sm font-medium text-slate-500 hover:text-slate-900">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de erro (substitui o alert nativo) --}}
    <div
        x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail.name === 'quill-error') open = true"
        x-on:close-modal.window="if ($event.detail.name === 'quill-error') open = false"
        x-show="open"
        x-cloak
        class="{{ $modal }}"
    >
        <div class="absolute inset-0 bg-slate-900/50" @click="open = false"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="mb-4 text-lg font-semibold text-slate-800">Não foi possível enviar</h3>
            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 ring-1 ring-rose-100">
                        <x-lucide-alert-triangle class="h-5 w-5 text-rose-500" />
                    </div>
                    <p class="text-sm leading-relaxed text-slate-600" x-text="errorMessage"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="open = false" class="rounded-lg bg-violet-600 px-6 py-2 text-sm font-semibold text-white hover:bg-violet-700">Entendi</button>
                </div>
            </div>
        </div>
    </div>
</div>
