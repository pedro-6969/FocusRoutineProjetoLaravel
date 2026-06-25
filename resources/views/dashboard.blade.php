@extends('layouts.app')

@section('title', 'Dashboard - Focus Routine')

@section('content')
@php
    /*
        O controller pode enviar:
        - $task, como você começou usando
        - ou $tasks, que é o nome mais comum

        Este trecho aceita os dois para evitar erro.
    */
    $tasks = collect($tasks ?? $task ?? []);

    /*
        Caso o controller já envie os contadores, usamos eles.
        Caso não envie, calculamos aqui.
    */
    $pending_task = $pending_task ?? $tasks->filter(fn ($item) => strtolower($item->status ?? '') === 'pending')->count();
    $completed_task = $completed_task ?? $tasks->filter(fn ($item) => strtolower($item->status ?? '') === 'completed')->count();
    $total_task = $total_task ?? $tasks->count();
@endphp

<div class="app-wrapper">
    {{-- Menu lateral para desktop --}}
    <aside class="app-sidebar d-none d-lg-block">
        <div class="text-center mb-4">
            <span class="fr-logo fr-logo-dark mx-auto">
                <i class="bi bi-bullseye"></i>
            </span>
        </div>

        <nav>
            <a href="{{ url('/dashboard') }}" class="sidebar-link active" title="Dashboard">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ url('/calendar') }}" class="sidebar-link" title="Calendário">
                <i class="bi bi-calendar3"></i>
            </a>

            <a href="{{ url('/task/create') }}" class="sidebar-link" title="Nova tarefa">
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

    {{-- Conteúdo principal --}}
    <main class="content-area">
        {{-- Barra superior no mobile --}}
        <header class="mobile-topbar">
            <div class="fr-brand">
                <span class="fr-logo">
                    <i class="bi bi-bullseye"></i>
                </span>
                <span>Focus Routine</span>
            </div>

            <a href="{{ url('/task/create') }}" class="text-white">
                <i class="bi bi-plus-circle fs-4"></i>
            </a>
        </header>

        {{-- Cabeçalho --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">
                    Welcome back, {{ Auth::user()->name ?? 'Alex' }}!
                </h1>
                <p class="text-muted mb-0">
                    Organize your day and track your productivity.
                </p>
            </div>

            <a href="{{ url('/task/create') }}" class="btn btn-fr-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add new task
            </a>
        </div>

        {{-- Cards de resumo --}}
        <section class="row g-3 mb-4">
            <div class="col-12 col-lg-7">
                <div class="welcome-card h-100">
                    <h2 class="h5 fw-bold mb-2">Organize your day and stay focused</h2>
                    <p class="mb-0 opacity-75">
                        Keep track of your pending and completed tasks, and keep your routine organized.
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="summary-card text-center">
                            <div class="fw-bold h4 mb-0">{{ $pending_task }}</div>
                            <small class="text-muted">Pendentes</small>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="summary-card text-center">
                            <div class="fw-bold h4 mb-0">{{ $completed_task }}</div>
                            <small class="text-muted">Concluídas</small>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="summary-card text-center">
                            <div class="fw-bold h4 mb-0">{{ $total_task }}</div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Lista de tarefas --}}
        <section>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Task List</h2>
                <a href="{{ url('/calendar') }}" class="small text-muted">
                    Ver calendário
                </a>
            </div>

            @if ($tasks->count())
                <div class="row g-3">
                    @foreach ($tasks as $item)
                        
                            /*
                                Preparação visual de prioridade.
                                Aceita Low, Medium, High ou Baixa, Média, Alta.
                            */
                            $priority = $item->priority ?? 'Medium';
                            $priorityLower = strtolower($priority);

                            $priorityClass = match ($priorityLower) {
                                'high', 'alta' => 'priority-high',
                                'low', 'baixa' => 'priority-low',
                                default => 'priority-medium',
                            };

                            /*
                                Categoria da tarefa.
                                Funciona se existir relacionamento category().
                            */
                            $categoryName = optional($item->category)->name ?? 'Sem categoria';

                            /*
                                Datas e horários.
                            */
                            $date = !empty($item->task_date)
                                ? \Carbon\Carbon::parse($item->task_date)->format('d/m')
                                : '--/--';

                            $time = !empty($item->task_time)
                                ? \Carbon\Carbon::parse($item->task_time)->format('H:i')
                                : '--:--';

                            $isCompleted = strtolower($item->status ?? '') === 'completed';
                        

                        <div class="col-12 col-md-6 col-xl-4">
                            <article class="task-card">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="task-icon">
                                            <i class="bi bi-check2-square"></i>
                                        </span>

                                        <div>
                                            <h3 class="h6 fw-bold mb-0">{{ $item->title }}</h3>
                                            <small class="text-muted">{{ $categoryName }}</small>
                                        </div>
                                    </div>

                                    <span class="priority-badge {{ $priorityClass }}">
                                        {{ $priority }}
                                    </span>
                                </div>

                                <p class="small text-muted mb-3">
                                    {{ $item->description ?? 'Sem descrição cadastrada.' }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $date }} às {{ $time }}
                                    </small>

                                    <div class="d-flex gap-1">
                                        {{-- Botão para concluir tarefa --}}
                                        @if (!$isCompleted)
                                            <form method="POST" action="{{ url('/task/' . $item->id . '/complete') }}">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-fr-outline" title="Concluir">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="status-completed small fw-bold">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                        @endif

                                        {{-- Botão para excluir tarefa --}}
                                        <form method="POST" action="{{ url('/task/' . $item->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-fr-outline"
                                                title="Excluir"
                                                onclick="return confirm('Deseja excluir esta tarefa?')"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Mensagem caso o usuário ainda não tenha tarefas --}}
                <div class="fr-card p-4 text-center">
                    <i class="bi bi-list-check display-5 text-muted"></i>
                    <h3 class="h5 fw-bold mt-3">Nenhuma tarefa cadastrada</h3>
                    <p class="text-muted mb-3">
                        Comece criando sua primeira tarefa para organizar sua rotina.
                    </p>

                    <a href="{{ url('/task/create') }}" class="btn btn-fr-primary">
                        Criar primeira tarefa
                    </a>
                </div>
            @endif
        </section>

        {{-- Navegação inferior para mobile --}}
        <nav class="bottom-nav">
            <a href="{{ url('/dashboard') }}" class="active">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ url('/calendar') }}">
                <i class="bi bi-calendar3"></i>
            </a>

            <a href="{{ url('/task/create') }}">
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