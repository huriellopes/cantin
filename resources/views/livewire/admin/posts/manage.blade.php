<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}" wire:navigate class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100" aria-label="{{ __('common.back') }}">
                @svg('lucide-arrow-left', 'h-5 w-5')
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $editingId ? __('crud_posts.modal_edit_title') : __('crud_posts.modal_new_title') }}</h2>
                <p class="text-sm text-slate-500">{{ __('crud_posts.subtitle') }}</p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-admin.input label="{{ __('crud_posts.field_title') }}" name="titleField" wire:model="titleField" />
                <x-admin.input label="{{ __('crud_posts.field_slug') }}" name="slug" wire:model="slug" />

                <div class="space-y-1">
                    <label for="category_id" class="block text-sm font-medium text-slate-700">{{ __('crud_posts.field_category') }}</label>
                    <select id="category_id" wire:model="category_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                        <option value="">{{ __('crud_posts.select_placeholder') }}</option>
                        @foreach ($categories as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <x-admin.input label="{{ __('crud_posts.field_published_at') }}" name="published_at" type="date" wire:model="published_at" />
            </div>

            <div class="mt-4 space-y-1">
                <label for="image" class="block text-sm font-medium text-slate-700">{{ __('crud_posts.field_image') }}</label>
                @if ($currentImage && ! $image)
                    <p class="text-xs text-slate-500">{{ __('crud_posts.current_image', ['name' => $currentImage]) }}</p>
                @endif
                <input type="file" id="image" wire:model="image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100">
                <div wire:loading wire:target="image" class="text-xs text-slate-400">{{ __('crud_posts.uploading_image') }}</div>
                @error('image') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-slate-700">{{ __('crud_posts.field_content') }}</label>
            <x-admin.quill-editor wire:model="content" :initial="$content" />
            @error('content') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.posts.index') }}" wire:navigate class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</a>
            <button type="submit" class="rounded-lg bg-violet-600 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                <span wire:loading.remove wire:target="save">{{ __('common.save') }}</span>
                <span wire:loading wire:target="save">{{ __('common.saving') }}</span>
            </button>
        </div>
    </form>
</div>
