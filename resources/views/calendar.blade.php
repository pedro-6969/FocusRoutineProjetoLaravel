@extends('layouts.app')

@section('title', 'Calendário - Focus Routine')

@section('content')
@php
    /*
        Aceita $task ou $tasks vindos do controller.
    */
    $tasks = collect($tasks ?? $task ?? []);

    /*
        Mês atual ou mês enviado pela URL:
        Exemplo: /calendar?month=2026-06
    */
    try {
        $currentMonth = request('month')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', request('month') . '-01')
            : now()->startOfMonth();
    } catch (\Exception $e) {
        $currentMonth = now()->startOfMonth();
    }

    /*
        Início e fim da grade do calendário.
    */
    $start = $currentMonth->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $end = $currentMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $days = [];

    for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
        $days[] = $day->copy();
    }

    /*
        Função para formatar a data das tarefas.
    */
    $formatDate = function ($value) {
        if (!$value) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    };

    /*
        Agrupa tarefas pela data.
    */
    $tasksByDate = $tasks
        ->filter(fn ($item) => $formatDate($item->task_date ?? null))
        ->groupBy(fn ($item) => $formatDate($item->task_date ?? null));

    /*
        Data selecionada.
    */
    $selectedDate = request('date', now()->format('Y-m-d'));

    try {
        $selectedCarbon = \Carbon\Carbon::parse($selectedDate);
    } catch (\Exception $e) {
        $selectedCarbon = now();
        $selectedDate = now()->format('Y-m-d');
    }

    $selectedTasks = $tasksByDate->get($selectedDate, collect());

    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

    $weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
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
            <a href="{{ url('/dashboard') }}" class="sidebar-link" title="Dashboard">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ url('/calendar') }}" class="sidebar-link active" title="Calendário">
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

    <main class="content-area">
        {{-- Topbar mobile --}}
        <header class="mobile-topbar">
            <div class="fr-brand">
                <span class="fr-logo">
                    <i class="bi bi-bullseye"></i>
                </span>
                <span>Calendar</span>
            </div>

            <a href="{{ url('/task/create') }}" class="text-white">
                <i class="bi bi-plus-circle fs-4"></i>
            </a>
        </header>

        {{-- Cabeçalho do calendário --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">Calendar</h1>
                <p class="text-muted mb-0">
                    View your tasks organized by date.
                </p>
            </div>

            <a href="{{ url('/task/create') }}" class="btn btn-fr-primary">
                <i class="bi bi-plus-lg me-1"></i>
                New task.
            </a>
        </div>

        <div class="row g-4">
            {{-- Calendário principal --}}
            <div class="col-12 col-xl-8">
                <section class="fr-card p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        {{-- Mês anterior --}}
                        <a href="{{ url('/calendar?month=' . $previousMonth) }}" class="btn btn-sm btn-fr-outline">
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        {{-- Nome do mês --}}
                        <h2 class="h5 fw-bold mb-0 text-capitalize">
                            {{ $currentMonth->translatedFormat('F Y') }}
                        </h2>

                        {{-- Próximo mês --}}
                        <a href="{{ url('/calendar?month=' . $nextMonth) }}" class="btn btn-sm btn-fr-outline">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    {{-- Dias da semana --}}
                    <div class="calendar-grid mb-2">
                        @foreach ($weekDays as $weekDay)
                            <div class="calendar-weekday">
                                {{ $weekDay }}
                            </div>
                        @endforeach
                    </div>

                    {{-- Dias do mês --}}
                    <div class="calendar-grid">
                        @foreach ($days as $day)
                            @php
                                $dateKey = $day->format('Y-m-d');
                                $dayTasks = $tasksByDate->get($dateKey, collect());
                                $isCurrentMonth = $day->month === $currentMonth->month;
                                $isActive = $dateKey === $selectedDate;
                            @endphp

                            <a
                                href="{{ url('/calendar?month=' . $currentMonth->format('Y-m') . '&date=' . $dateKey) }}"
                                class="calendar-day {{ !$isCurrentMonth ? 'out-month' : '' }} {{ $isActive ? 'active' : '' }}"
                            >
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong>{{ $day->format('d') }}</strong>

                                    @if ($dayTasks->count())
                                        <span class="calendar-dot"></span>
                                    @endif
                                </div>

                                @if ($dayTasks->count())
                                    <small class="d-none d-md-block text-muted mt-2">
                                        {{ $dayTasks->count() }} task(s)
                                    </small>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Tarefas do dia selecionado --}}
            <div class="col-12 col-xl-4">
                <aside class="fr-card p-3 p-md-4">
                    <h2 class="h5 fw-bold mb-1">Tasks of the day</h2>

                    <p class="text-muted small mb-4">
                        {{ $selectedCarbon->format('d/m/Y') }}
                    </p>

                    @if ($selectedTasks->count())
                        <div class="d-flex flex-column gap-3">
                            @foreach ($selectedTasks as $item)
                                @php
                                    $time = !empty($item->task_time)
                                        ? \Carbon\Carbon::parse($item->task_time)->format('H:i')
                                        : '--:--';
                                @endphp

                                <article class="task-card">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <h3 class="h6 fw-bold mb-1">
                                                {{ $item->title }}
                                            </h3>

                                            <p class="small text-muted mb-2">
                                                {{ $item->description ?? 'No description.' }}
                                            </p>
                                        </div>

                                        <small class="text-muted">
                                            {{ $time }}
                                        </small>
                                    </div>

                                    <span class="priority-badge priority-medium">
                                        {{ $item->priority ?? 'Medium' }}
                                    </span>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-check display-6 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">
                                No tasks for this day.
                            </p>
                        </div>
                    @endif
                </aside>
            </div>
        </div>

        {{-- Navegação inferior mobile --}}
        <nav class="bottom-nav">
            <a href="{{ url('/dashboard') }}">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ url('/calendar') }}" class="active">
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