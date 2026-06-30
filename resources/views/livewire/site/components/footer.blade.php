<div>
    <footer class="bg-slate-900 text-slate-300">
        <div class="mx-auto flex max-w-7xl flex-col gap-10 px-6 py-14 md:flex-row md:items-start md:justify-between">
            {{-- Marca --}}
            <div class="max-w-xs">
                <a href="{{ route('site.home') }}" wire:navigate class="inline-block">
                    <img src="{{ $logo }}" alt="CaNTIn" class="h-16 w-auto" />
                </a>
                <p class="mt-3 text-sm text-slate-400">
                    Cadastro Nacional de Terreiros Inclusivos — acolhimento, respeito e visibilidade para pessoas trans.
                </p>
            </div>

            {{-- Páginas --}}
            @if (! empty($static_pages) && count($static_pages) > 0)
                <div>
                    <h5 class="text-sm font-semibold uppercase tracking-wider text-slate-200">Páginas</h5>
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
                <h5 class="text-sm font-semibold uppercase tracking-wider text-slate-200">Contato</h5>
                <p class="mt-4 font-semibold text-white">Babalorixá Alan T'Ogun</p>
                <p class="mt-1 text-sm text-slate-400">(61) 9 9977-6608</p>
                <p class="text-sm text-slate-400">seggvg@gmail.com</p>
                <div class="mt-4 flex gap-3 md:justify-end">
                    @foreach ([
                        ['https://www.facebook.com/alan.baloni', 'facebook'],
                        ['https://www.instagram.com/alanbaloni79/', 'instagram'],
                        ['https://www.linkedin.com/in/prof-m-sc-jorge-alan-baloni-21932b299/', 'linkedin'],
                    ] as [$url, $icon])
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-300 transition hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 hover:text-white">
                            @svg('lucide-'.$icon, 'h-5 w-5')
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-800 px-6 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ date('Y') }} CaNTIn</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('site.privacy') }}" wire:navigate class="transition hover:text-white">Política de Privacidade</a>
                <a href="{{ route('site.guidelines') }}" wire:navigate class="transition hover:text-white">Diretrizes</a>
            </div>
            <p>Desenvolvido pela Empresa Hurvion Systems</p>
        </div>
    </footer>
</div>
