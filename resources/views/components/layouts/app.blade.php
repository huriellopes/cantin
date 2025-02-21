<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Huriel Lopes">
    <meta name="google-site-verification" content="YE-utvBYDHJCzV1g7jBT6a79BatD-F31NOT849JDLyM" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('/assets/images/cantin.ico') }}" type="image/x-icon">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />

    @if (app()->isProduction())
        <link rel="stylesheet" type="application/json" href="{{ asset('/build/manifest.json') }}"/>
    @else
        @vite(['resources/js/app.js'])
    @endif

    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark" wire:navigate>
        <div class="container">
            <a class="navbar-brand" href="{{ route('site.home') }}" wire:navigate>
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            @include('components.layouts.partials.menu')
        </div>
    </nav>

    {{ $slot }}

    @livewire('cantin.components.whatsapp-button')

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/all.min.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4VSY21XL8V"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag(){dataLayer.push(arguments);}

        gtag('js', new Date());

        gtag('config', 'G-4VSY21XL8V');
    </script>
    @livewireScripts
</body>
</html>
