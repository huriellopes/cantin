@extends('Site.layouts.app')

@section('css')
    <style></style>
@stop

@include('Site.layouts.includes.banner')

@section('content')
    <div class="row mt-5">
        <div class="col mt-2">
            <h2 class="text-center">Parceiros</h2>
        </div>
    </div>
    <div class="row" id="partners">
    </div>

    <div class="row mt-3">
        <div class="col">
            <h2 class="text-center">Perguntas Frequentes</h2>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="accordion accordion-flush border" id="accordionFlushExample">
                @if (count($commons) > 0)
                    @foreach ($commons as $common)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $common->id }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $common->id }}">
                                    {{ $common->question }}
                                </button>
                            </h2>
                            <div id="flush-collapse{{ $common->id }}" class="accordion-collapse collapse"
                                 data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {{ $common->answer }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Do que se trata o projeto Cantin?
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, aut distinctio enim eveniet ex facere fugit laborum magnam minima nam neque officia perspiciatis placeat possimus, quae ratione sequi suscipit. Inventore.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            Somente pessoas da Comunidade LGBTQIA+ podem se cadastrar?
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, aut distinctio enim eveniet ex facere fugit laborum magnam minima nam neque officia perspiciatis placeat possimus, quae ratione sequi suscipit. Inventore.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            Como faço para me cadastrar?
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam architecto deleniti dignissimos dolorem, doloremque, exercitationem facilis fugit ipsam laborum nam necessitatibus nobis, obcaecati officia possimus quasi quia ut vero voluptates!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('assets/js/partners/listPartners.js') }}"></script>
@stop
