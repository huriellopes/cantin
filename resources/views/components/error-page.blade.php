@props(['code' => '500', 'title' => 'Algo deu errado', 'message' => ''])
<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} · {{ $title }} · CaNTIn</title>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-full place-items-center bg-gradient-to-br from-violet-50 to-pink-50 px-6 py-16 text-slate-800">
    <div class="w-full max-w-lg text-center">
        <p class="bg-gradient-to-r from-violet-600 to-pink-500 bg-clip-text text-7xl font-black tracking-tight text-transparent sm:text-8xl">
            {{ $code }}
        </p>
        <h1 class="mt-4 text-2xl font-bold text-slate-800 sm:text-3xl">{{ $title }}</h1>
        @if ($message)
            <p class="mx-auto mt-3 max-w-md text-slate-500">{{ $message }}</p>
        @endif

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:brightness-110">
                Voltar ao início
            </a>
            <button onclick="history.back()" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-medium text-slate-600 transition hover:bg-white">
                Voltar à página anterior
            </button>
        </div>

        <p class="mt-10 text-2xl font-extrabold tracking-tight text-slate-300">
            Ca<span class="text-violet-300">NTI</span>n
        </p>
    </div>
</body>
</html>
