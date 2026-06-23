<!doctype html>
<html lang="pt-BR">
<head>
    {{-- Configurações básicas da página --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Focus Routine')</title>

    {{-- Bootstrap via CDN para facilitar o uso no projeto --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons para os ícones das telas --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS geral do sistema --}}
    

    @stack('styles')
</head>

<body>
    {{-- Mensagens de sucesso ou erro vindas do Controller --}}
    @if (session('success') || session('error'))
        <div class="container pt-3">
            @if (session('success'))
                <div class="alert alert-success rounded-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger rounded-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    {{-- Conteúdo principal de cada página --}}
    @yield('content')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>