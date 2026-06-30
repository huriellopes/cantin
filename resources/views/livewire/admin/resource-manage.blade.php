<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route($indexRoute) }}" wire:navigate class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100" aria-label="{{ __('common.back') }}">
            @svg('lucide-arrow-left', 'h-5 w-5')
        </a>
        <h2 class="text-xl font-bold text-slate-800">
            {{ $editingId ? __('crud_resource.edit_title', ['singular' => $singular]) : __('crud_resource.new_title', ['singular' => $singular]) }}
        </h2>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($fields as $name => $cfg)
                    @continue(($cfg['type'] ?? 'text') === 'richtext')
                    <div class="space-y-1">
                        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $cfg['label'] ?? \Illuminate\Support\Str::headline($name) }}</label>
                        @if (($cfg['type'] ?? 'text') === 'textarea')
                            <textarea id="{{ $name }}" wire:model="form.{{ $name }}" rows="5" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                        @else
                            <input type="{{ $cfg['type'] ?? 'text' }}" id="{{ $name }}" wire:model="form.{{ $name }}" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                        @endif
                        @error("form.{$name}") <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>
        </div>

        @foreach ($fields as $name => $cfg)
            @if (($cfg['type'] ?? 'text') === 'richtext')
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">{{ $cfg['label'] ?? \Illuminate\Support\Str::headline($name) }}</label>
                    <x-admin.quill-editor wire:model="form.{{ $name }}" :initial="$form[$name] ?? ''" />
                    @error("form.{$name}") <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            @endif
        @endforeach

        <div class="flex justify-end gap-2">
            <a href="{{ route($indexRoute) }}" wire:navigate class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</a>
            <button type="submit" class="rounded-lg bg-violet-600 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                <span wire:loading.remove wire:target="save">{{ __('common.save') }}</span>
                <span wire:loading wire:target="save">{{ __('common.saving') }}</span>
            </button>
        </div>
    </form>
</div>
