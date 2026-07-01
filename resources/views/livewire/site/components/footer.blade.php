<div>
    <footer class="bg-slate-900 text-slate-300">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-10 px-6 py-14 text-center md:flex-row md:items-start md:justify-between md:text-left">
            {{-- Marca --}}
            <div class="max-w-xs">
                <a href="{{ route('site.home') }}" wire:navigate class="inline-block">
                    <img src="{{ asset('assets/images/cantin-logo.webp') }}" alt="CaNTIn" width="160" height="160" class="h-16 w-auto" loading="lazy" decoding="async" />
                </a>
                <p class="mt-3 text-sm text-slate-400">
                    {{ __('footer.tagline') }}
                </p>
            </div>

            {{-- Páginas --}}
            @if (! empty($static_pages) && count($static_pages) > 0)
                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-200">{{ __('footer.pages') }}</h2>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($static_pages as $page)
                            <li>
                                <a href="{{ route('site.static.page', $page->slug) }}" wire:navigate class="text-slate-400 transition hover:text-white">
                                    {{ str($page->name)->ucfirst() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Contato --}}
            <div class="md:text-right">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-200">{{ __('footer.contact') }}</h2>
                <p class="mt-4 font-semibold text-white">Babalorixá Alan T'Ogun</p>
                <p class="mt-1 text-sm text-slate-400">(61) 9 9977-6608</p>
                <p class="text-sm text-slate-400">seggvg@gmail.com</p>
                <div class="mt-4 flex justify-center gap-3 md:justify-end">
                    @foreach ([
                        ['https://www.facebook.com/alan.baloni', 'facebook'],
                        ['https://www.instagram.com/alanbaloni79/', 'instagram'],
                        ['https://www.linkedin.com/in/prof-m-sc-jorge-alan-baloni-21932b299/', 'linkedin'],
                    ] as [$url, $icon])
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                           aria-label="{{ ucfirst($icon) }}" title="{{ ucfirst($icon) }}"
                           class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-300 transition hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 hover:text-white">
                            @svg('lucide-'.$icon, 'h-5 w-5')
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center gap-3 border-t border-slate-800 px-6 py-5 text-center text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:text-left">
            <p>© {{ date('Y') }} CaNTIn</p>
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
                <a href="{{ route('site.privacy') }}" wire:navigate class="transition hover:text-white">{{ __('footer.privacy') }}</a>
                <a href="{{ route('site.guidelines') }}" wire:navigate class="transition hover:text-white">{{ __('footer.guidelines') }}</a>
            </div>
            <p>{{ __('footer.developed_by') }}</p>
        </div>
    </footer>
</div>
