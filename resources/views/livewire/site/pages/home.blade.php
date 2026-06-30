<div>
    {{-- Hero --}}
    <section class="relative flex min-h-[100svh] items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $image }}')"></div>
        {{-- Overlay bem leve: a imagem fica em destaque, com leve tint para legibilidade --}}
        <div class="absolute inset-0 bg-gradient-to-br from-violet-900/40 via-violet-900/25 to-pink-800/30"></div>

        <div class="relative mx-auto max-w-4xl px-6 text-center">
            <span class="inline-block rounded-full bg-white/15 px-4 py-1 text-sm font-medium text-white ring-1 ring-white/30 backdrop-blur">
                Acolhimento, respeito e axé 💜
            </span>
            <h1 class="mt-6 text-4xl font-extrabold leading-tight text-white sm:text-5xl md:text-6xl">
                Cadastro Nacional de<br>
                <span class="bg-gradient-to-r from-pink-300 to-sky-300 bg-clip-text text-transparent">Terreiros Inclusivos</span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-white/90">
                Conectando pessoas trans a espaços religiosos que acolhem, respeitam e valorizam a transgeneridade em todo o Brasil.
            </p>
            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('site.terreiros.create') }}" wire:navigate
                   class="rounded-full bg-white px-7 py-3 font-semibold text-violet-700 shadow-lg transition hover:scale-105 hover:shadow-xl">
                    Cadastre seu terreiro
                </a>
                <a href="{{ route('site.terreiros.search') }}" wire:navigate
                   class="rounded-full bg-white/10 px-7 py-3 font-semibold text-white ring-1 ring-white/40 backdrop-blur transition hover:bg-white/20">
                    Encontrar terreiros
                </a>
            </div>
        </div>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70">
            @svg('lucide-chevron-down', 'h-6 w-6 animate-bounce')
        </div>
    </section>

    {{-- Valores --}}
    <section class="mx-auto max-w-7xl px-6 py-20">
        <div class="grid gap-8 md:grid-cols-3">
            @foreach ([
                ['💜', 'Acolhimento', 'Mapeamos casas que recebem pessoas trans com respeito e dignidade.'],
                ['🌎', 'Alcance nacional', 'Um mapeamento que conecta pessoas a terreiros inclusivos em todo o Brasil.'],
                ['✨', 'Visibilidade', 'Damos visibilidade a sacerdotes trans e a práticas inclusivas já existentes.'],
            ] as [$emoji, $titulo, $texto])
                <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-100 to-pink-100 text-2xl">{{ $emoji }}</div>
                    <h3 class="mt-5 text-xl font-bold text-slate-800">{{ $titulo }}</h3>
                    <p class="mt-2 text-slate-600">{{ $texto }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Parceiros --}}
    @if (! empty($partners) && count($partners) > 0)
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-800">Entidades parceiras</h2>
                    <p class="mt-2 text-slate-500">Organizações que caminham conosco nessa missão.</p>
                </div>
                <div class="mt-12 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($partners as $partner)
                        <div class="flex flex-col items-center gap-3 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($partner->path_image) }}" alt="{{ $partner->name }}" class="h-20 w-20 rounded-xl object-cover" loading="lazy">
                            <span class="text-center text-sm font-medium text-slate-700">{{ $partner->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if (! empty($commons) && count($commons) > 0)
        <section class="mx-auto max-w-3xl px-6 py-20">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-slate-800">Perguntas frequentes</h2>
                <p class="mt-2 text-slate-500">Tire suas dúvidas sobre o projeto.</p>
            </div>
            <div class="mt-10 space-y-3" x-data="{ open: null }">
                @foreach ($commons as $i => $common)
                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                        <button @click="open = (open === {{ $i }} ? null : {{ $i }})" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left font-medium text-slate-800 hover:bg-slate-50">
                            <span>{{ $common->question }}</span>
                            <span class="shrink-0 text-violet-500 transition-transform" :class="open === {{ $i }} && 'rotate-180'">@svg('lucide-chevron-down', 'h-5 w-5')</span>
                        </button>
                        <div x-show="open === {{ $i }}" x-transition x-cloak class="max-w-none px-5 pb-5 text-slate-600">
                            {!! $common->answer !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA final --}}
    <section class="px-6 pb-24">
        <div class="mx-auto max-w-5xl overflow-hidden rounded-[2rem] bg-gradient-to-r from-violet-600 to-pink-500 px-8 py-14 text-center shadow-xl">
            <h2 class="text-3xl font-bold text-white">Seu terreiro acolhe pessoas trans?</h2>
            <p class="mx-auto mt-3 max-w-2xl text-white/90">Faça parte do mapeamento nacional e ajude a construir uma rede de espaços religiosos inclusivos.</p>
            <a href="{{ route('site.terreiros.create') }}" wire:navigate class="mt-8 inline-block rounded-full bg-white px-8 py-3 font-semibold text-violet-700 shadow-lg transition hover:scale-105">
                Quero cadastrar
            </a>
        </div>
    </section>
</div>
