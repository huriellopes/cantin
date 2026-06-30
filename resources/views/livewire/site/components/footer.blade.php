<div>
    <footer class="bg-slate-900 text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-3">
            {{-- Marca --}}
            <div>
                <a href="{{ route('site.home') }}" wire:navigate class="text-2xl font-extrabold text-white">
                    Ca<span class="bg-gradient-to-r from-violet-400 to-pink-400 bg-clip-text text-transparent">NTI</span>n
                </a>
                <p class="mt-3 max-w-xs text-sm text-slate-400">
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

        <div class="border-t border-slate-800 py-5 text-center text-xs text-slate-500">
            © {{ date('Y') }} CaNTIn · Desenvolvido por Huriel Lopes
        </div>
    </footer>
</div>
