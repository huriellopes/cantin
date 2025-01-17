<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="shortcut icon" href="{{ asset('/assets/images/cantin.ico') }}" type="image/x-icon" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />

    <style>
        .banner-topo {
            background-image: url({{ asset('/assets/images/background-outro.png') }});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top center;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner-topo .content-banner {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        footer {
            display: flex;
            padding: 25px;
            width: 100%;
            height: 40px; /* Define a altura do rodapé */
        }

        footer .container {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
        }
    </style>
    @yield('css')
</head>
<body>
    @include('Site.layouts.includes.menu')

    @yield('content-banner')

    <div class="container mb-4">
        @yield('content')
    </div>
{{--    @include('Site.layouts.includes.footer')--}}
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/all.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>
    <script src="{{ asset('assets/js/functions.all.js') }}"></script>
    @yield('js')
</body>
</html>
