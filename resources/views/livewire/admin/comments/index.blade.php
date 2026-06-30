<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">{{ __('crud_comments.title') }}</h2>
        <p class="text-sm text-slate-500">{{ __('crud_comments.subtitle') }}</p>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <x-admin.table-toolbar :options="$this->perPageOptions()" />

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="body" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_comments.column_comment') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_comments.column_user') }}</th>
                        <th class="px-4 py-3">{{ __('crud_comments.column_post') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($comments as $comment)
                        <tr class="hover:bg-slate-50" wire:key="comment-{{ $comment->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $comment->id }}</td>
                            <td class="px-4 py-3 max-w-xs text-slate-700">{{ \Illuminate\Support\Str::limit($comment->body, 80) }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $comment->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $comment->post?->title }}</td>
                            <td class="px-4 py-3">
                                <x-admin.badge :color="$comment->status?->getColor() ?? 'slate'">{{ $comment->status?->label() }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $comment->id }})" />
                                    @if ($comment->post)
                                        <a href="{{ route('site.blog.show', $comment->post->slug) }}" target="_blank" title="{{ __('crud_comments.view_on_site') }}" aria-label="{{ __('crud_comments.view_on_site') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100">
                                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                    @endif
                                    <x-admin.action icon="reply" color="violet" label="{{ __('crud_comments.reply') }}" wire:click="reply({{ $comment->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$comment->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$comment->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                        wire:click="confirmToggle({{ $comment->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_comments.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $comments->links() }}</div>
    </div>

    <x-admin.modal title="{{ __('crud_comments.reply_modal_title') }}">
        <form wire:submit="save" class="space-y-4">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">{{ __('crud_comments.original_comment') }}</label>
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">{{ $originalBody }}</div>
            </div>
            <div class="space-y-1">
                <label for="body" class="block text-sm font-medium text-slate-700">{{ __('crud_comments.your_reply') }}</label>
                <textarea id="body" wire:model="body" rows="4" maxlength="500"
                          class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                @error('body') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('crud_comments.reply') }}</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
