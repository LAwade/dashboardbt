<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Minha Aplicação')</title>

    @vite('resources/js/app.js') {{-- Laravel 9+ com Vite --}}
</head>

<body class="bg-light p-4">
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
