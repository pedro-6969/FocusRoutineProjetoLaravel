@extends('layouts.app')

@section('title', 'Focus Routine - Página Inicial')

@section('content')
<main class="landing-page">
    <section class="fr-card landing-box">
        {{-- Cabeçalho azul da página inicial --}}
        <div class="landing-header">
            <div class="fr-brand justify-content-center mb-4">
                <span class="fr-logo">
                    <i class="bi bi-bullseye"></i>
                </span>
                <span>Focus Routine</span>
            </div>

            <h1 class="h3 fw-bold mb-2">Bem-vindo à Focus Routine</h1>
            <p class="mb-0 opacity-75">
                Organize sua rotina, acompanhe tarefas e melhore sua produtividade.
            </p>
        </div>

        {{-- Conteúdo com os botões principais --}}
        <div class="landing-content">
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-5">
                    {{-- Botão para login --}}
                    <a href="{{ route('login') }}" class="action-tile">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span class="fw-bold">Acessar Conta</span>
                    </a>
                </div>

                <div class="col-12 col-md-5">
                    {{-- Botão para cadastro --}}
                    <a href="{{ route('register') }}" class="action-tile">
                        <i class="bi bi-person-plus"></i>
                        <span class="fw-bold">Criar Cadastro</span>
                    </a>
                </div>
            </div>

            <p class="text-center text-muted small mt-4 mb-0">
                Their app description is a clean, modern, intuitive, secure and silent productivity.
            </p>
        </div>
    </section>
</main>
@endsection