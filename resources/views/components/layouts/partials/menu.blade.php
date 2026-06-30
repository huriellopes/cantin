<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mb-2 mb-lg-0 d-flex ms-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.home') ? 'active' : '' }}" aria-current="page" href="{{ route('site.home') }}" wire:navigate>
                {{ __('Home') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.about') ? 'active' : '' }}" aria-current="page" href="{{ route('site.about') }}" wire:navigate>
                {{ __('About') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.partners-entities') ? 'active' : '' }}" aria-current="page" href="{{ route('site.partners-entities') }}" wire:navigate>
                {{ __('Partners Entities') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.trans-people') ? 'active' : '' }}" aria-current="page" href="{{ route('site.trans-people') }}" wire:navigate>
                {{ __('Trans People') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.terreiros.search') ? 'active' : '' }}" aria-current="page" href="{{ route('site.terreiros.search') }}" wire:navigate>
                {{ __('Terreiros') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.blog.posts') ? 'active' : '' }}" aria-current="page" href="{{ route('site.blog.posts') }}" wire:navigate>
                {{ __('Blog') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('site.links.external') ? 'active' : '' }}" aria-current="page" href="{{ route('site.links.external') }}" wire:navigate>
                {{ __('External Links') }}
            </a>
        </li>

        @if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
            <li class="nav-item">
                <a class="nav-link" onclick="window.location.href = '{{ route('admin.dashboard') }}'" style="cursor: pointer;">
                    Painel do Admin
                </a>
            </li>
        @elseif (auth()->check() && auth()->user()->hasRole('user'))
            <li class="nav-item">
                <a class="nav-link" onclick="window.location.href = '{{ route('site.home') }}'" style="cursor: pointer;">
                    Painel do Usuário
                </a>
            </li>
        @else
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link {{ request()->routeIs('site.auth.login') ? 'active' : '' }}" aria-current="page" href="{{ route('site.auth.login') }}" wire:navigate>--}}
{{--                    {{ __('Access Restricted') }}--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-item">
                <a class="nav-link" onclick="window.location.href = '{{ route('site.auth.login') }}'" style="cursor: pointer;">
                    {{ __('Access Restricted') }}
                </a>
            </li>
        @endif
    </ul>
</div>
