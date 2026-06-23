@extends('layouts.app')

@section('title', 'Cadastro - Focus Routine')

@section('content')
<main class="auth-page">
    <section class="fr-card auth-card">
        {{-- Logo e título --}}
        <div class="text-center mb-4">
            <div class="fr-brand fr-brand-dark justify-content-center mb-2">
                <span class="fr-logo fr-logo-dark">
                    <i class="bi bi-bullseye"></i>
                </span>
                <span>Focus Routine</span>
            </div>

            <h1 class="h5 fw-bold mb-1">Criar Novo Cadastro</h1>
            <p class="text-muted small mb-0">Crie sua conta para organizar suas tarefas.</p>
        </div>

        {{-- Formulário padrão de cadastro do Breeze --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nome do usuário --}}
            <div class="mb-3">
                <label for="name" class="form-label small fw-bold">Nome</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Digite seu nome"
                    required
                    autofocus
                >

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Digite seu email"
                    required
                >

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Senha --}}
            <div class="mb-3">
                <label for="password" class="form-label small fw-bold">Senha</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Digite sua senha"
                    required
                >

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirmação de senha --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label small fw-bold">
                    Confirmação de Senha
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirme sua senha"
                    required
                >
            </div>

            {{-- Botão de cadastro --}}
            <button type="submit" class="btn btn-fr-primary w-100">
                Criar Cadastro
            </button>

            {{-- Link para login --}}
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-muted">
                    Já tem conta? Acessar login
                </a>
            </div>
        </form>
    </section>
</main>
@endsection