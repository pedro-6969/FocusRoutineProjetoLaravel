@extends('layouts.app')

@section('title', 'Login - Focus Routine')

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

            <h1 class="h5 fw-bold mb-1">Acessar Login</h1>
            <p class="text-muted small mb-0">Entre para gerenciar sua rotina.</p>
        </div>

        {{-- Formulário padrão de login do Breeze --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Campo de email --}}
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
                    autofocus
                >

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo de senha --}}
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

            {{-- Lembrar login --}}
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label small text-muted">
                    Manter conectado
                </label>
            </div>

            {{-- Botão de envio --}}
            <button type="submit" class="btn btn-fr-primary w-100">
                Acessar Conta
            </button>

            {{-- Link para cadastro --}}
            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="small text-muted">
                    Ainda não tem conta? Criar cadastro
                </a>
            </div>
        </form>
    </section>
</main>
@endsection