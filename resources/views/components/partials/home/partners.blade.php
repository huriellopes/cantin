@props(['partners'])

@assets
<style>
    /* Estilos para o carrossel */
    #parceirosCarousel .carousel-control-prev,
    #parceirosCarousel .carousel-control-next {
        width: 5%;
        opacity: 0.8; /* Seta um pouco mais opaca para elegância */
    }

    /* Centralizando as setas verticalmente */
    #parceirosCarousel .carousel-control-prev-icon,
    #parceirosCarousel .carousel-control-next-icon {
        background-color: #343a40; /* Fundo escuro para a seta */
        border-radius: 50%;
        padding: 20px;
        background-size: 50%; /* Ajusta o tamanho da imagem da seta */
        background-repeat: no-repeat;
        background-position: center;
    }

    /* Ícones das setas em SVG branco */
    #parceirosCarousel .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
    }

    #parceirosCarousel .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    /* Estilos para os cards */
    .partner-card {
        border: none; /* Remove a borda padrão do card */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil para destacar */
        transition: transform 0.3s ease-in-out; /* Efeito de transição */
        border-radius: 15px; /* Bordas arredondadas */
        overflow: hidden; /* Garante que o conteúdo não saia da borda */
    }

    .partner-card:hover {
        transform: translateY(-5px); /* Efeito "flutuante" ao passar o mouse */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra mais forte no hover */
    }

    .partner-card-img {
        height: 150px; /* Altura fixa para todas as imagens */
        object-fit: contain; /* Redimensiona a imagem para caber sem cortar */
        padding: 15px; /* Espaçamento interno para a imagem */
    }

    .partner-card-body {
        background-color: #f8f9fa; /* Fundo claro para o corpo do card */
        padding: 1rem;
        border-top: 1px solid #dee2e6; /* Linha sutil separando imagem e título */
    }

    /* Oculta os títulos dos parceiros */
    .partner-card .card-title {
        /*display: none;*/
        font-size: 0.9rem;
        font-weight: 500;
    }

</style>
@endassets

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="text-center mb-4 text-dark">Nossos Parceiros</h2>
            <div id="parceirosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($partners->chunk(5) as $key => $chunk)
                        <div class="carousel-item @if($loop->first) active @endif">
                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4 align-items-stretch">
                                @foreach($chunk as $partner)
                                    <div class="col">
                                        <div class="card h-100 partner-card" title="{{ $partner->name }}">
                                            <img src="{{ asset("storage/$partner->path_image") }}" class="card-img-top partner-card-img" alt="{{ $partner->name }}">
                                            <div class="card-body partner-card-body text-center">
                                                <span class="card-title">{{ username($partner->name) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#parceirosCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#parceirosCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>
    </div>
</div>
