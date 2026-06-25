@extends('layouts.app')

@section('title', 'Nova Tarefa - Focus Routine')

@section('content')
@php
    /*
        O ideal é o controller enviar as categorias do banco:
        $categories = Category::all();

        Caso ainda não esteja enviando, usamos exemplos visuais.
    */
    $categories = collect($categories ?? [
        (object) ['id' => 1, 'name' => 'Study', 'icon' => 'bi-mortarboard'],
        (object) ['id' => 2, 'name' => 'Work', 'icon' => 'bi-briefcase'],
        (object) ['id' => 3, 'name' => 'Personal', 'icon' => 'bi-person'],
        (object) ['id' => 4, 'name' => 'Saúde', 'icon' => 'bi-heart-pulse'],
    ]);
@endphp

<div class="app-wrapper">
    {{-- Menu lateral desktop --}}
    <aside class="app-sidebar d-none d-lg-block">
        <div class="text-center mb-4">
            <span class="fr-logo fr-logo-dark mx-auto">
                <i class="bi bi-bullseye"></i>
            </span>
        </div>

        <nav>
            <a href="{{ route('dashboard')}}" class="sidebar-link" title="Dashboard">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ route('calendar.show') }}" class="sidebar-link" title="Calendário">
                <i class="bi bi-calendar3"></i>
            </a>

            <a href="{{ route('task.create') }}" class="sidebar-link active" title="Nova tarefa">
                <i class="bi bi-plus-lg"></i>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="text-center mt-4">
                @csrf

                <button type="submit" class="sidebar-link border-0 bg-transparent" title="Sair">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </nav>
    </aside>

    <main class="content-area">
        {{-- Topbar mobile --}}
        <header class="mobile-topbar">
            <div class="fr-brand">
                <span class="fr-logo">
                    <i class="bi bi-bullseye"></i>
                </span>
                <span>Nova Tarefa</span>
            </div>

            <a href="{{ url('/dashboard') }}" class="text-white">
                <i class="bi bi-house fs-4"></i>
            </a>
        </header>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <section class="fr-card p-4">
                    {{-- Título da página --}}
                    <div class="text-center mb-4">
                        <h1 class="h4 fw-bold mb-1">Criar Nova Tarefa</h1>
                        <p class="text-muted mb-0">
                            Cadastre uma tarefa para organizar sua rotina.
                        </p>
                    </div>

                    {{-- Formulário de criação de tarefa --}}
                    <form method="POST" action="{{ route('task.store') }}">
                        @csrf

                        {{-- Título --}}
                        <div class="mb-3">
                            <label for="title" class="form-label small fw-bold">Título</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Ex: Estudar inglês"
                                required
                            >

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-3">
                            <label for="description" class="form-label small fw-bold">Descrição</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Descreva os detalhes da tarefa"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Categoria --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Categoria</label>

                            <div class="row g-2">
                                @foreach ($categories as $category)
                                    <div class="col-6 col-md-3">
                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="category_id"
                                            id="category_{{ $category->id }}"
                                            value="{{ $category->id }}"
                                            {{ old('category_id', $categories->first()->id ?? null) == $category->id ? 'checked' : '' }}
                                        >

                                        <label for="category_{{ $category->id }}" class="category-option w-100">
                                            <i class="bi {{ $category->icon ?? 'bi-folder' }} d-block fs-4 mb-1"></i>
                                            <span class="small">{{ $category->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Prioridade --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Prioridade</label>

                            <div class="d-flex gap-2 flex-wrap">
                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="priority"
                                    id="priority_low"
                                    value="Low"
                                    {{ old('priority', 'Medium') === 'Low' ? 'checked' : '' }}
                                >
                                <label class="btn btn-fr-outline" for="priority_low">
                                    Low
                                </label>

                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="priority"
                                    id="priority_medium"
                                    value="Medium"
                                    {{ old('priority', 'Medium') === 'Medium' ? 'checked' : '' }}
                                >
                                <label class="btn btn-fr-outline" for="priority_medium">
                                    Medium
                                </label>

                                <input
                                    type="radio"
                                    class="btn-check"
                                    name="priority"
                                    id="priority_high"
                                    value="High"
                                    {{ old('priority', 'Medium') === 'High' ? 'checked' : '' }}
                                >
                                <label class="btn btn-fr-outline" for="priority_high">
                                    High
                                </label>
                            </div>

                            @error('priority')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Data e hora --}}
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="task_date" class="form-label small fw-bold">Data</label>
                                <input
                                    type="date"
                                    id="task_date"
                                    name="task_date"
                                    value="{{ old('task_date') }}"
                                    class="form-control @error('task_date') is-invalid @enderror"
                                    required
                                >

                                @error('task_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="task_time" class="form-label small fw-bold">Tempo</label>
                                <input
                                    type="time"
                                    id="task_time"
                                    name="task_time"
                                    value="{{ old('task_time') }}"
                                    class="form-control @error('task_time') is-invalid @enderror"
                                >

                                @error('task_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status inicial da tarefa --}}
                        <input type="hidden" name="status" value="Pending">

                        {{-- Botão de envio --}}
                        <button type="submit" class="btn btn-fr-primary w-100">
                            Criar Tarefa
                        </button>
                    </form>
                </section>
            </div>
        </div>

        {{-- Navegação inferior mobile --}}
        <nav class="bottom-nav">
            <a href="{{ url('/dashboard') }}">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ url('/calendar') }}">
                <i class="bi bi-calendar3"></i>
            </a>

            <a href="{{ url('/task/create') }}" class="active">
                <i class="bi bi-plus-lg"></i>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </nav>
    </main>
</div>
@endsection