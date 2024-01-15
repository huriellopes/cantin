<ul class="navbar-nav mb-2 mb-lg-0 d-flex ms-auto">
    @foreach($params as $param)
        <li class="nav-item">
            <a class="nav-link" aria-current="page"
               href="{{ route($param->route) ?? '#' }}">
                {{ str()->ucfirst($param->name) }}
            </a>
        </li>
    @endforeach

    @if (Route::has('login'))
        @auth
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ url('/dashboard') }}">
                    Dashboard
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route('login') }}">
                    Área Restrita
                </a>
            </li>
        @endauth
    @endif
</ul>
