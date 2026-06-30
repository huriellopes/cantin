<div class="mx-auto max-w-4xl px-6 py-16">
    @if (! empty($page))
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ $page->name }}</h1>
        <div class="mt-6 space-y-4 text-slate-600 [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-800 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:text-violet-600">
            {!! $page->content !!}
        </div>
    @else
        <header class="text-center">
            <span class="inline-block rounded-full bg-violet-100 px-4 py-1 text-sm font-medium text-violet-700">Sobre o projeto</span>
            <h1 class="mt-4 text-3xl font-extrabold text-slate-800 sm:text-4xl">Cadastro Nacional de Terreiros Inclusivos</h1>
        </header>

        <div class="mt-10 grid gap-10 lg:grid-cols-3">
            <article class="space-y-6 text-slate-600 lg:col-span-2">
                <p>
                    É uma iniciativa pioneira e transformadora, criada para mapear e tornar acessíveis os terreiros que acolhem, respeitam e valorizam a transgeneridade em sua totalidade. Com o objetivo de conectar pessoas trans a espaços religiosos inclusivos em todo o Brasil, o CaNTIn também realiza um mapeamento nacional, promovendo visibilidade para sacerdotes e sacerdotisas trans/travestis e para as práticas inclusivas já existentes nesses locais.
                </p>
                <h2 class="text-2xl font-bold text-slate-800">Origem e Propósito</h2>
                <p>
                    O projeto foi impulsionado a partir da pesquisa de mestrado <i>ÌGBÀMÍRÀN ÀIYÉ</i>: O Ethos Afro-Brasileiro e a Transgeneridade na Religião dos Orixás, de autoria do Babalorixá Alan de Ogun (Ogundeje). Inspirado pela necessidade de unir espiritualidade e diversidade, o <b>CaNTIn</b> busca consolidar o papel das religiões de matriz africana como espaços de acolhimento, resistência e transformação social. Mais do que um simples cadastro, o <b>CaNTIn</b> é uma ferramenta que permite às pessoas <b>trans, travestis e não-binárias</b> identificarem terreiros comprometidos com o respeito à sua identidade de gênero, enquanto destaca lideranças religiosas e ações inclusivas em todo o país.
                </p>
            </article>
            <div class="lg:col-span-1">
                <img src="{{ $image }}" alt="CaNTIn" class="w-full rounded-3xl object-cover shadow-lg" />
            </div>
        </div>

        <div class="mt-10 space-y-6 text-slate-600">
            <h2 class="text-2xl font-bold text-slate-800">Contribuições e Impacto</h2>
            <p>
                A idealização do <b>CaNTIn</b> contou com a colaboração de <b>Egbon Adeloyá OjáBará</b>, pessoa não-binária e referência em debates sobre diversidade nas religiões afro-brasileiras. Adeloyá idealizou o cadastro, reforçando valores de acolhimento e inclusão que estão no cerne das tradições dos Orixás, e a pesquisa do <b>Mestre e Babalorixá Alan de Ogun</b> foi essencial para organizar a maneira como esse cadastro se tornaria orgânico e acessível na internet.
            </p>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['Registro de Terreiros', 'Lideranças religiosas podem cadastrar seus espaços, detalhando práticas inclusivas.'],
                    ['Busca Facilitada', 'Pessoas trans podem localizar terreiros em suas regiões, encontrando acolhimento e respeito.'],
                    ['Rede de Apoio', 'Entidades parceiras podem se cadastrar, ampliando o alcance e o impacto do projeto.'],
                ] as [$t, $d])
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <h3 class="font-semibold text-violet-700">{{ $t }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $d }}</p>
                    </div>
                @endforeach
            </div>

            <h2 class="text-2xl font-bold text-slate-800">Um Movimento de Inclusão e Resistência</h2>
            <p>
                O <b>CaNTIn</b> vai além de um simples registro: é um símbolo de luta pela igualdade e pelo respeito à pluralidade humana. Ao promover o diálogo entre espiritualidade e diversidade, o projeto contribui para o fortalecimento das religiões afro-brasileiras como espaços de acolhimento e transformação social.
            </p>
        </div>

        <div class="mt-12 rounded-3xl bg-gradient-to-r from-violet-600 to-pink-500 px-8 py-10 text-center text-white">
            <h3 class="text-2xl font-bold">Participe do CaNTIn</h3>
            <p class="mx-auto mt-2 max-w-2xl text-white/90">Se você é uma liderança religiosa ou deseja encontrar terreiros trans-inclusivos, junte-se a este movimento.</p>
            <a href="{{ route('site.terreiros.create') }}" wire:navigate class="mt-6 inline-block rounded-full bg-white px-7 py-3 font-semibold text-violet-700 shadow-lg transition hover:scale-105">Cadastrar terreiro</a>
        </div>
    @endif
</div>
