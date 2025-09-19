<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-12 w-auto">
            <h1 class="text-center">
                {{ str($page->name)->ucfirst() }}
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12 w-auto">
            {!! $page->content !!}
        </div>
    </div>
</div>
