<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_posts.title') }}</h2>
            <p class="text-sm text-slate-500">{{ __('crud_posts.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" wire:navigate title="{{ __('crud_posts.new_post') }}" aria-label="{{ __('crud_posts.new_post') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:px-4">
            @svg('lucide-plus', 'h-4 w-4')
            <span class="hidden sm:inline">{{ __('crud_posts.new_post') }}</span>
        </a>
    </div>


    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="title" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_posts.col_title') }}</x-admin.th>
                        <x-admin.th column="published_at" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_posts.col_published') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_posts.col_likes') }}</th>
                        <x-admin.th column="views" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_posts.col_views') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_posts.col_author') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-slate-50" wire:key="post-{{ $post->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $post->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $post->published_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3"><x-admin.badge color="primary">{{ $post->likes_count }}</x-admin.badge></td>
                            <td class="px-4 py-3"><x-admin.badge color="success">{{ $post->views }}</x-admin.badge></td>
                            <td class="px-4 py-3 text-slate-600">{{ $post->user?->name }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$post->status?->getColor() ?? 'slate'">{{ $post->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $post->id }})" />
                                    <a href="{{ route('site.blog.show', $post->slug) }}" target="_blank" title="{{ __('crud_posts.view_on_site') }}" aria-label="{{ __('crud_posts.view_on_site') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100">
                                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" wire:navigate title="{{ __('common.edit') }}" aria-label="{{ __('common.edit') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-violet-600 transition hover:bg-violet-50">
                                        @svg('lucide-pencil', 'h-[18px] w-[18px]')
                                    </a>
                                    @if ($post->status === \App\Enum\StatusPost::PUBLISHED)
                                        <x-admin.action icon="unpublish" color="amber" label="{{ __('crud_posts.unpublish') }}" wire:click="unpublish({{ $post->id }})" />
                                    @else
                                        <x-admin.action icon="publish" color="emerald" label="{{ __('crud_posts.publish') }}" wire:click="publish({{ $post->id }})" />
                                    @endif
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $post->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-slate-400">{{ __('crud_posts.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $posts->links() }}</div>
    </div>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
