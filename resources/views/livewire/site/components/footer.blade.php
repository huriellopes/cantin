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
                                <a href="{{ route('site.static.page', $page->slug) }}" class="text-slate-400 transition hover:text-white">
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
                        ['https://www.facebook.com/alan.baloni', 'M22 12a10 10 0 1 0-11.5 9.9v-7H8v-2.9h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6v1.9H16l-.4 2.9h-2.1v7A10 10 0 0 0 22 12Z'],
                        ['https://www.instagram.com/alanbaloni79/', 'M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.1.4.3 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.1-1 .3-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.3-2.2-.4a3.8 3.8 0 0 1-1.4-.9 3.8 3.8 0 0 1-.9-1.4c-.1-.4-.3-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.1 1-.3 2.2-.4C8.4 2.2 8.8 2.2 12 2.2Zm0 4.9a4.9 4.9 0 1 0 0 9.8 4.9 4.9 0 0 0 0-9.8Zm0 8.1a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm6.2-8.3a1.1 1.1 0 1 1-2.3 0 1.1 1.1 0 0 1 2.3 0Z'],
                        ['https://www.linkedin.com/in/prof-m-sc-jorge-alan-baloni-21932b299/', 'M20.5 2h-17A1.5 1.5 0 0 0 2 3.5v17A1.5 1.5 0 0 0 3.5 22h17a1.5 1.5 0 0 0 1.5-1.5v-17A1.5 1.5 0 0 0 20.5 2ZM8 19H5V9h3v10ZM6.5 7.7a1.8 1.8 0 1 1 0-3.5 1.8 1.8 0 0 1 0 3.5ZM19 19h-3v-5c0-1.4-.5-2.1-1.5-2.1s-1.5.7-1.5 2.1v5h-3V9h3v1.2c.5-.8 1.4-1.5 2.7-1.5 1.9 0 3.3 1.2 3.3 3.8V19Z'],
                    ] as [$url, $path])
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-300 transition hover:bg-gradient-to-r hover:from-violet-500 hover:to-pink-500 hover:text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
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
