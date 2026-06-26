@extends('layouts.app')

@section('title', 'Edit Task - Focus Routine')

@section('content')

    <div class="app-wrapper">

        {{-- =========================================================
            SIDEBAR — DESKTOP
        ========================================================== --}}
        <aside class="app-sidebar d-none d-lg-block">

            {{-- Logo do sistema --}}
            <div class="text-center mb-4">
                <span class="fr-logo fr-logo-dark">
                    <i class="bi bi-bullseye"></i>
                </span>
            </div>

            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link"
                title="Dashboard"
            >
                <i class="bi bi-house"></i>
            </a>

            {{-- Criar tarefa --}}
            <a
                href="{{ route('task.create') }}"
                class="sidebar-link active"
                title="New Task"
            >
                <i class="bi bi-plus-square"></i>
            </a>

            {{-- Criar categoria --}}
            <a
                href="{{ route('category.create') }}"
                class="sidebar-link"
                title="New Category"
            >
                <i class="bi bi-tags"></i>
            </a>

            {{-- Calendário --}}
            @if (\Illuminate\Support\Facades\Route::has('calendar.index'))
                <a
                    href="{{ route('calendar.index') }}"
                    class="sidebar-link"
                    title="Calendar"
                >
                    <i class="bi bi-calendar3"></i>
                </a>
            @endif

        </aside>

        {{-- =========================================================
            CONTEÚDO PRINCIPAL
        ========================================================== --}}
        <main class="content-area">

            {{-- Topbar mobile --}}
            @include('partials.mobile-topbar')

            <div class="container-fluid">

                {{-- =================================================
                    CABEÇALHO
                ================================================== --}}
                <div
                    class="d-flex justify-content-between align-items-center
                           flex-wrap gap-3 mb-4"
                >
                    <div>
                        <h2 class="fw-bold mb-1">
                            Edit Task
                        </h2>

                        <p class="text-muted mb-0">
                            Update the information, category and status of your task.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="btn btn-fr-outline"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Dashboard
                    </a>
                </div>

                {{-- =================================================
                    MENSAGEM DE SUCESS
                ================================================== --}}
                @if (session('success'))
                    <div
                        class="alert alert-success rounded-3"
                        role="alert"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- =================================================
                    MENSAGENS DE ERRO
                ================================================== --}}
                @if ($errors->any())
                    <div
                        class="alert alert-danger rounded-3"
                        role="alert"
                    >
                        <div class="fw-bold mb-2">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Check the fields below:
                        </div>

                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- =================================================
                    CARD DO FORMULÁRIO
                ================================================== --}}
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-7">

                        <div class="fr-card p-4 p-md-5">

                            {{-- Identidade visual --}}
                            <div class="text-center mb-4">

                                <div
                                    class="fr-brand fr-brand-dark
                                           justify-content-center mb-3"
                                >
                                    <span class="fr-logo fr-logo-dark">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>

                                    <span>Focus Routine</span>
                                </div>

                                <h3 class="fw-bold mb-2">
                                    Edit Your Task
                                </h3>

                                <p class="text-muted mb-0">
                                    Change the task information and save your updates.
                                </p>

                            </div>

                            {{-- =====================================
                                FORMULÁRIO

                                PATCH é utilizado porque estamos
                                atualizando um registro existente.
                            ====================================== --}}
                            <form
                                action="{{ route('task.update', $task->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                {{-- =================================
                                    TÍTULO
                                ================================== --}}
                                <div class="mb-4">

                                    <label
                                        for="title"
                                        class="form-label fw-semibold"
                                    >
                                        Task Title
                                    </label>

                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        class="form-control
                                               @error('title') is-invalid @enderror"
                                        value="{{ old('title', $task->title) }}"
                                        placeholder="Example: Study Laravel"
                                        maxlength="255"
                                        required
                                        autofocus
                                    >

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- =================================
                                    DESCRIÇÃO
                                ================================== --}}
                                <div class="mb-4">

                                    <label
                                        for="description"
                                        class="form-label fw-semibold"
                                    >
                                        Description
                                    </label>

                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-control
                                               @error('description') is-invalid @enderror"
                                        rows="4"
                                        placeholder="Add details about this task..."
                                    >{{ old('description', $task->description) }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- =================================
                                    CATEGORIA

                                    A tarefa salva apenas category_id.
                                    As categorias pertencem ao usuário.
                                ================================== --}}
                                <div class="mb-4">

                                    <div
                                        class="d-flex justify-content-between
                                               align-items-center gap-2 mb-2"
                                    >
                                        <label
                                            for="category_id"
                                            class="form-label fw-semibold mb-0"
                                        >
                                            Category
                                        </label>

                                        <a
                                            href="{{ route('category.create') }}"
                                            class="small text-decoration-none"
                                        >
                                            <i class="bi bi-plus-circle"></i>
                                            Create category
                                        </a>
                                    </div>

                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select
                                               @error('category_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">
                                            Select a category
                                        </option>

                                        @foreach ($categories as $item)
                                            <option
                                                value="{{ $item->id }}"
                                                @selected(
                                                    old(
                                                        'category_id',
                                                        $task->category_id
                                                    ) == $item->id
                                                )
                                            >
                                                {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @else
                                        <div class="form-text">
                                            Choose one of your categories for this task.
                                        </div>
                                    @enderror

                                </div>

                                {{-- =================================
                                    PRIORIDADE
                                ================================== --}}
                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Priority
                                    </label>

                                    <p class="text-muted small mb-3">
                                        High priority tasks appear first on the dashboard.
                                    </p>

                                    <div class="row g-2">

                                        {{-- Low --}}
                                        <div class="col-12 col-sm-4">

                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="priority"
                                                id="priority-low"
                                                value="Low"
                                                autocomplete="off"
                                                @checked(
                                                    old(
                                                        'priority',
                                                        $task->priority
                                                    ) === 'Low'
                                                )
                                            >

                                            <label
                                                for="priority-low"
                                                class="category-option w-100 h-100"
                                            >
                                                <span class="priority-badge priority-low">
                                                    Low
                                                </span>

                                                <small class="text-muted d-block mt-2">
                                                    Can be done later
                                                </small>
                                            </label>

                                        </div>

                                        {{-- Medium --}}
                                        <div class="col-12 col-sm-4">

                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="priority"
                                                id="priority-medium"
                                                value="Medium"
                                                autocomplete="off"
                                                @checked(
                                                    old(
                                                        'priority',
                                                        $task->priority
                                                    ) === 'Medium'
                                                )
                                            >

                                            <label
                                                for="priority-medium"
                                                class="category-option w-100 h-100"
                                            >
                                                <span class="priority-badge priority-medium">
                                                    Medium
                                                </span>

                                                <small class="text-muted d-block mt-2">
                                                    Needs attention
                                                </small>
                                            </label>

                                        </div>

                                        {{-- High --}}
                                        <div class="col-12 col-sm-4">

                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="priority"
                                                id="priority-high"
                                                value="High"
                                                autocomplete="off"
                                                @checked(
                                                    old(
                                                        'priority',
                                                        $task->priority
                                                    ) === 'High'
                                                )
                                            >

                                            <label
                                                for="priority-high"
                                                class="category-option w-100 h-100"
                                            >
                                                <span class="priority-badge priority-high">
                                                    High
                                                </span>

                                                <small class="text-muted d-block mt-2">
                                                    Must be done first
                                                </small>
                                            </label>

                                        </div>

                                    </div>

                                    @error('priority')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- =================================
                                    DATA E HORÁRIO
                                ================================== --}}
                                <div class="row g-3 mb-4">

                                    {{-- Data --}}
                                    <div class="col-12 col-md-6">

                                        <label
                                            for="task_date"
                                            class="form-label fw-semibold"
                                        >
                                            Task Date
                                        </label>

                                        <input
                                            type="date"
                                            id="task_date"
                                            name="task_date"
                                            class="form-control
                                                   @error('task_date') is-invalid @enderror"
                                            value="{{ old(
                                                'task_date',
                                                \Carbon\Carbon::parse(
                                                    $task->task_date
                                                )->format('Y-m-d')
                                            ) }}"
                                            required
                                        >

                                        @error('task_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Horário --}}
                                    <div class="col-12 col-md-6">

                                        <label
                                            for="task_time"
                                            class="form-label fw-semibold"
                                        >
                                            Task Time
                                        </label>

                                        <input
                                            type="time"
                                            id="task_time"
                                            name="task_time"
                                            class="form-control
                                                   @error('task_time') is-invalid @enderror"
                                            value="{{ old(
                                                'task_time',
                                                $task->task_time
                                                    ? \Carbon\Carbon::parse(
                                                        $task->task_time
                                                    )->format('H:i')
                                                    : ''
                                            ) }}"
                                        >

                                        @error('task_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @else
                                            <div class="form-text">
                                                Time is optional.
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                                {{-- =================================
                                    STATUS
                                ================================== --}}
                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Task Status
                                    </label>

                                    <p class="text-muted small mb-3">
                                        Change the status according to your progress.
                                    </p>

                                    <div class="row g-2">

                                        {{-- Pending --}}
                                        <div class="col-12 col-sm-6">

                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="status"
                                                id="status-pending"
                                                value="Pending"
                                                autocomplete="off"
                                                @checked(
                                                    old(
                                                        'status',
                                                        $task->status
                                                    ) === 'Pending'
                                                )
                                            >

                                            <label
                                                for="status-pending"
                                                class="category-option w-100 h-100"
                                            >
                                                <i
                                                    class="bi bi-clock-history
                                                           fs-4 status-pending"
                                                ></i>

                                                <strong class="d-block mt-2">
                                                    Pending
                                                </strong>

                                                <small class="text-muted">
                                                    Task still needs to be completed
                                                </small>
                                            </label>

                                        </div>

                                        {{-- Completed --}}
                                        <div class="col-12 col-sm-6">

                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="status"
                                                id="status-completed"
                                                value="Completed"
                                                autocomplete="off"
                                                @checked(
                                                    old(
                                                        'status',
                                                        $task->status
                                                    ) === 'Completed'
                                                )
                                            >

                                            <label
                                                for="status-completed"
                                                class="category-option w-100 h-100"
                                            >
                                                <i
                                                    class="bi bi-check-circle
                                                           fs-4 status-completed"
                                                ></i>

                                                <strong class="d-block mt-2">
                                                    Completed
                                                </strong>

                                                <small class="text-muted">
                                                    Task has already been completed
                                                </small>
                                            </label>

                                        </div>

                                    </div>

                                    @error('status')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- =================================
                                    RESUMO DA TAREFA
                                ================================== --}}
                                <div class="alert alert-light border rounded-3 mb-4">

                                    <div class="d-flex gap-2">

                                        <i class="bi bi-info-circle text-primary"></i>

                                        <div>
                                            <strong>Current task information</strong>

                                            <p class="small text-muted mb-0 mt-1">
                                                Category:
                                                {{ $task->category?->name ?? 'No category' }}

                                                <br>

                                                Current priority:
                                                {{ $task->priority }}

                                                <br>

                                                Current status:
                                                {{ $task->status }}
                                            </p>
                                        </div>

                                    </div>

                                </div>

                                {{-- =================================
                                    BOTÕES
                                ================================== --}}
                                <div class="d-grid gap-2">

                                    <button
                                        type="submit"
                                        class="btn btn-fr-primary py-2"
                                    >
                                        <i class="bi bi-check-circle me-1"></i>
                                        Save Changes
                                    </button>

                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="btn btn-fr-outline py-2"
                                    >
                                        Cancel
                                    </a>

                                </div>

                            </form>

                            {{-- =====================================
                                EXCLUSÃO DA TAREFA

                                Fica fora do formulário de atualização
                                porque utiliza outra rota e DELETE.
                            ====================================== --}}
                            <hr class="my-4">

                            <div class="text-center">

                                <p class="text-muted small mb-2">
                                    This action permanently deletes the task.
                                </p>

                                <form
                                    action="{{ route('task.destroy', $task->id) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Are you sure you want to delete this task?'
                                    )"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                    >
                                        <i class="bi bi-trash me-1"></i>
                                        Delete Task
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

            {{-- =====================================================
                NAVEGAÇÃO MOBILE
            ====================================================== --}}
            @include('partials.bottom-nav')

        </main>
    </div>
