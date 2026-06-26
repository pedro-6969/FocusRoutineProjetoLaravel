@extends('layouts.app')

@section('title', 'Calendário - Focus Routine')

@section('content')

<div class="app-wrapper">

    {{-- =========================================================
        MENU LATERAL DO DESKTOP
    ========================================================== --}}
    @include('partials.sidebar')

    {{-- =========================================================
        CONTEÚDO PRINCIPAL
    ========================================================== --}}
    <main class="content-area">

        {{-- Topbar para celular --}}
        @include('partials.mobile-topbar')

        {{-- =====================================================
            CABEÇALHO
        ====================================================== --}}
        <div
            class="d-flex flex-column flex-md-row
                   justify-content-between align-items-md-center
                   gap-3 mb-4"
        >
            <div>
                <h1 class="h4 fw-bold mb-1">
                    Calendar
                </h1>

                <p class="text-muted mb-0">
                    View your pending tasks organized by date.
                </p>
            </div>

            <a
                href="{{ route('task.create') }}"
                class="btn btn-fr-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                New task
            </a>
        </div>

        <div class="row g-4">

            {{-- =================================================
                CALENDÁRIO
            ================================================== --}}
            <div class="col-12 col-xl-8">

                <section class="fr-card p-3 p-md-4">

                    {{-- Navegação entre meses --}}
                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <a
                            href="{{ route('calendar.show', [
                                'month' => $previousMonth
                            ]) }}"
                            class="btn btn-sm btn-fr-outline"
                            title="Previous Month"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        <h2 class="h5 fw-bold mb-0 text-capitalize">
                            {{ $currentMonth->translatedFormat('F Y') }}
                        </h2>

                        <a
                            href="{{ route('calendar.show', [
                                'month' => $nextMonth
                            ]) }}"
                            class="btn btn-sm btn-fr-outline"
                            title="Next Month"
                        >
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

                    {{-- Grade de dias --}}
                    <div class="calendar-grid">

                        @foreach ($days as $day)

                            @php
                                $dateKey = $day->format('Y-m-d');

                                $dayTasks = $tasksByDate->get(
                                    $dateKey,
                                    collect()
                                );

                                $isCurrentMonth = $day->month
                                    === $currentMonth->month;

                                $isSelected = $dateKey === $selectedDate;

                                $isToday = $day->isToday();
                            @endphp

                            <a
                                href="{{ route('calendar.show', [
                                    'month' => $currentMonth->format('Y-m'),
                                    'date' => $dateKey
                                ]) }}"
                                class="
                                    calendar-day
                                    {{ ! $isCurrentMonth ? 'out-month' : '' }}
                                    {{ $isSelected ? 'active' : '' }}
                                    {{ $isToday ? 'calendar-day-today' : '' }}
                                "
                                title="{{ $dayTasks->count() }} task(s)"
                            >

                                {{-- Número do dia --}}
                                <div
                                    class="d-flex justify-content-between
                                           align-items-start"
                                >
                                    <strong>
                                        {{ $day->format('d') }}
                                    </strong>

                                    @if ($dayTasks->isNotEmpty())
                                        <span class="calendar-dot"></span>
                                    @endif
                                </div>

                                {{-- Prévia das tarefas no desktop --}}
                                @if ($dayTasks->isNotEmpty())

                                    <div
                                        class="calendar-task-preview-list
                                               d-none d-md-flex"
                                    >
                                        @foreach ($dayTasks->take(2) as $calendarTask)

                                            @php
                                                $calendarPriorityClass = match (
                                                    $calendarTask->priority
                                                ) {
                                                    'High' => 'priority-high',
                                                    'Medium' => 'priority-medium',
                                                    'Low' => 'priority-low',
                                                    default => '',
                                                };
                                            @endphp

                                            <span
                                                class="calendar-task-preview
                                                       {{ $calendarPriorityClass }}"
                                            >
                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $calendarTask->title,
                                                        16
                                                    )
                                                }}
                                            </span>

                                        @endforeach

                                        @if ($dayTasks->count() > 2)
                                            <small class="calendar-more-tasks">
                                                +{{ $dayTasks->count() - 2 }}
                                            </small>
                                        @endif
                                    </div>

                                    {{-- Contador simplificado no celular --}}
                                    <small
                                        class="calendar-mobile-count
                                               d-md-none"
                                    >
                                        {{ $dayTasks->count() }}
                                    </small>

                                @endif

                            </a>

                        @endforeach

                    </div>

                </section>

            </div>

            {{-- =================================================
                TAREFAS DO DIA SELECIONADO
            ================================================== --}}
            <div class="col-12 col-xl-4">

                <aside class="fr-card p-3 p-md-4 calendar-day-panel">

                    <div
                        class="d-flex justify-content-between
                               align-items-start gap-2 mb-4"
                    >
                        <div>
                            <h2 class="h5 fw-bold mb-1">
                                Tasks of the day
                            </h2>

                            <p class="text-muted small mb-0">
                                {{ $selectedCarbon->format('d/m/Y') }}
                            </p>
                        </div>

                        <span class="calendar-task-total">
                            {{ $selectedTasks->count() }}
                        </span>
                    </div>

                    @forelse ($selectedTasks as $item)

                        @php
                            $priorityClass = match ($item->priority) {
                                'High' => 'priority-high',
                                'Medium' => 'priority-medium',
                                'Low' => 'priority-low',
                                default => '',
                            };

                            $taskTime = $item->task_time
                                ? \Carbon\Carbon::parse(
                                    $item->task_time
                                )->format('H:i')
                                : null;
                        @endphp

                        <article class="task-card calendar-selected-task mb-3">

                            {{-- Cabeçalho da tarefa --}}
                            <div
                                class="d-flex justify-content-between
                                       align-items-start gap-2 mb-2"
                            >
                                <div>
                                    <small class="text-muted">
                                        {{
                                            $item->category?->name
                                            ?? 'No category'
                                        }}
                                    </small>

                                    <h3 class="h6 fw-bold mb-0">
                                        {{ $item->title }}
                                    </h3>
                                </div>

                                <span
                                    class="priority-badge
                                           {{ $priorityClass }}"
                                >
                                    {{ $item->priority }}
                                </span>
                            </div>

                            {{-- Descrição --}}
                            <p class="small text-muted mb-3">
                                {{
                                    $item->description
                                    ?: 'No description.'
                                }}
                            </p>

                            {{-- Horário --}}
                            <div
                                class="d-flex justify-content-between
                                       align-items-center"
                            >
                                <span class="small text-muted">
                                    <i class="bi bi-clock me-1"></i>

                                    {{ $taskTime ?? 'No time defined' }}
                                </span>

                                <div class="d-flex gap-2">

                                    {{-- Marcar como concluída --}}
                                    <form
                                        action="{{ route(
                                            'task.complete',
                                            $item->id
                                        ) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-fr-outline"
                                            title="Complete Task"
                                        >
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    {{-- Editar tarefa --}}
                                    <a
                                        href="{{ route(
                                            'task.edit',
                                            $item->id
                                        ) }}"
                                        class="btn btn-sm btn-fr-outline"
                                        title="Edit Task"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>
                            </div>

                        </article>

                    @empty

                        <div class="text-center py-5">

                            <i
                                class="bi bi-calendar-check
                                       display-6 text-muted"
                            ></i>

                            <h3 class="h6 fw-bold mt-3">
                                No tasks for this day
                            </h3>

                            <p class="text-muted small mb-3">
                                Add a task for this date to see it here.
                            </p>

                            <a
                                href="{{ route('task.create') }}"
                                class="btn btn-sm btn-fr-primary"
                            >
                                <i class="bi bi-plus-lg me-1"></i>
                                Create task
                            </a>

                        </div>

                    @endforelse

                </aside>

            </div>

        </div>

        {{-- =========================================================
            NAVEGAÇÃO MOBILE
        ========================================================== --}}
        @include('partials.bottom-nav')

    </main>

</div>

@endsection