@extends('layouts.app')

@section('title', 'Create Task - Focus Routine')

@section('content')

<div class="app-wrapper">

    {{-- =========================================================
        SIDEBAR DO DESKTOP
    ========================================================== --}}
    <aside class="app-sidebar d-none d-lg-block">

        {{-- Logo --}}
        <div class="text-center mb-4">
            <span class="fr-logo fr-logo-dark mx-auto">
                <i class="bi bi-bullseye"></i>
            </span>
        </div>

        <nav>
            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link"
                title="Dashboard"
            >
                <i class="bi bi-house"></i>
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

            {{-- Nova tarefa --}}
            <a
                href="{{ route('task.create') }}"
                class="sidebar-link active"
                title="New Task"
            >
                <i class="bi bi-plus-lg"></i>
            </a>

            {{-- Nova categoria --}}
            <a
                href="{{ route('category.create') }}"
                class="sidebar-link"
                title="New Category"
            >
                <i class="bi bi-tags"></i>
            </a>

            {{-- Logout --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                class="text-center mt-4"
            >
                @csrf

                <button
                    type="submit"
                    class="sidebar-link border-0 bg-transparent"
                    title="Logout"
                >
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </nav>
    </aside>

    {{-- =========================================================
        CONTEÚDO PRINCIPAL
    ========================================================== --}}
    <main class="content-area">

        {{-- Topbar mobile --}}
        <header class="mobile-topbar">
            <div class="fr-brand">
                <span class="fr-logo">
                    <i class="bi bi-bullseye"></i>
                </span>

                <span>Focus Routine</span>
            </div>

            <a
                href="{{ route('dashboard') }}"
                class="text-white"
                title="Dashboard"
            >
                <i class="bi bi-house fs-4"></i>
            </a>
        </header>

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
                        Create New Task
                    </h2>

                    <p class="text-muted mb-0">
                        Add a new activity to organize your routine.
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
                MENSAGENS
            ================================================== --}}

            @if (session('success'))
                <div class="alert alert-success rounded-3">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info rounded-3">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
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

                    <section class="fr-card p-4 p-md-5">

                        {{-- Identidade visual --}}
                        <div class="text-center mb-4">

                            <div
                                class="fr-brand fr-brand-dark
                                       justify-content-center mb-3"
                            >
                                <span class="fr-logo fr-logo-dark">
                                    <i class="bi bi-check2-square"></i>
                                </span>

                                <span>Focus Routine</span>
                            </div>

                            <h1 class="h4 fw-bold mb-2">
                                Create New Task
                            </h1>

                            <p class="text-muted mb-0">
                                Choose a category, priority, date and time
                                for your new task.
                            </p>

                        </div>

                        {{-- =========================================
                            FORMULÁRIO

                            O formulário envia os dados para store().
                        ========================================== --}}
                        <form
                            method="POST"
                            action="{{ route('task.store') }}"
                        >
                            @csrf

                            {{-- =====================================
                                TÍTULO
                            ====================================== --}}
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
                                    value="{{ old('title') }}"
                                    class="form-control
                                           @error('title') is-invalid @enderror"
                                    placeholder="Example: Study English"
                                    maxlength="255"
                                    autocomplete="off"
                                    autofocus
                                    required
                                >

                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- =====================================
                                DESCRIÇÃO
                            ====================================== --}}
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
                                    rows="4"
                                    class="form-control
                                           @error('description') is-invalid @enderror"
                                    placeholder="Add details about this task..."
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="form-text">
                                        Description is optional.
                                    </div>
                                @enderror

                            </div>

                            {{-- =====================================
                                CATEGORIA

                                As categorias vêm do Controller e
                                pertencem somente ao usuário logado.
                            ====================================== --}}
                            <div class="mb-4">

                                <div
                                    class="d-flex justify-content-between
                                           align-items-center gap-2 mb-2"
                                >
                                    <label class="form-label fw-semibold mb-0">
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

                                <p class="text-muted small mb-3">
                                    Select the category that best represents
                                    this task.
                                </p>

                                @if ($categories->isNotEmpty())

                                    <div class="category-selection-scroll">
                                        <div class="row g-2">

                                            @foreach ($categories as $category)
                                                <div class="col-6 col-md-4">

                                                    <input
                                                        type="radio"
                                                        class="btn-check"
                                                        name="category_id"
                                                        id="category-{{ $category->id }}"
                                                        value="{{ $category->id }}"
                                                        autocomplete="off"
                                                        @checked(
                                                            old('category_id')
                                                            == $category->id
                                                        )
                                                        required
                                                    >

                                                    <label
                                                        for="category-{{ $category->id }}"
                                                        class="category-option
                                                               w-100 h-100"
                                                    >
                                                        <span
                                                            class="category-color-dot
                                                                   category-{{ $category->color }}"
                                                        ></span>

                                                        <strong class="d-block mt-2">
                                                            {{ $category->name }}
                                                        </strong>
                                                    </label>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                @else

                                    {{-- 
                                        Essa mensagem é uma proteção extra.
                                        Normalmente o Controller redirecionará
                                        antes de chegar aqui.
                                    --}}
                                    <div class="alert alert-info rounded-3">
                                        <p class="mb-2">
                                            You do not have any categories yet.
                                        </p>

                                        <a
                                            href="{{ route('category.create') }}"
                                            class="btn btn-sm btn-fr-primary"
                                        >
                                            Create First Category
                                        </a>
                                    </div>

                                @endif

                                @error('category_id')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- =====================================
                                PRIORIDADE
                            ====================================== --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Priority
                                </label>

                                <p class="text-muted small mb-3">
                                    High priority tasks appear first on
                                    the dashboard.
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
                                                old('priority') === 'Low'
                                            )
                                            required
                                        >

                                        <label
                                            for="priority-low"
                                            class="category-option
                                                   w-100 h-100"
                                        >
                                            <span
                                                class="priority-badge
                                                       priority-low"
                                            >
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
                                                old('priority', 'Medium')
                                                === 'Medium'
                                            )
                                            required
                                        >

                                        <label
                                            for="priority-medium"
                                            class="category-option
                                                   w-100 h-100"
                                        >
                                            <span
                                                class="priority-badge
                                                       priority-medium"
                                            >
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
                                                old('priority') === 'High'
                                            )
                                            required
                                        >

                                        <label
                                            for="priority-high"
                                            class="category-option
                                                   w-100 h-100"
                                        >
                                            <span
                                                class="priority-badge
                                                       priority-high"
                                            >
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

                            {{-- =====================================
                                DATA E HORÁRIO
                            ====================================== --}}
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
                                        value="{{ old('task_date') }}"
                                        class="form-control
                                               @error('task_date') is-invalid @enderror"
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
                                        value="{{ old('task_time') }}"
                                        class="form-control
                                               @error('task_time') is-invalid @enderror"
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

                            {{-- =====================================
                                INFORMAÇÃO SOBRE O STATUS

                                O status não é enviado pelo formulário.
                                O Controller cria a tarefa como Pending.
                            ====================================== --}}
                            <div class="alert alert-light border rounded-3 mb-4">

                                <div class="d-flex gap-2">
                                    <i class="bi bi-info-circle text-primary"></i>

                                    <div>
                                        <strong>Initial task status</strong>

                                        <p class="small text-muted mb-0 mt-1">
                                            Every new task starts as Pending.
                                            You can mark it as Completed later
                                            on the dashboard.
                                        </p>
                                    </div>
                                </div>

                            </div>

                            {{-- =====================================
                                BOTÕES
                            ====================================== --}}
                            <div class="d-grid gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-fr-primary py-2"
                                    @disabled($categories->isEmpty())
                                >
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Create Task
                                </button>

                                <a
                                    href="{{ route('dashboard') }}"
                                    class="btn btn-fr-outline py-2"
                                >
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </section>

                </div>
            </div>

        </div>

        {{-- =========================================================
            NAVEGAÇÃO INFERIOR MOBILE
        ========================================================== --}}
        <nav class="bottom-nav">

            <a
                href="{{ route('dashboard') }}"
                title="Dashboard"
            >
                <i class="bi bi-house"></i>
            </a>

            @if (\Illuminate\Support\Facades\Route::has('calendar.index'))
                <a
                    href="{{ route('calendar.index') }}"
                    title="Calendar"
                >
                    <i class="bi bi-calendar3"></i>
                </a>
            @endif

            <a
                href="{{ route('task.create') }}"
                class="active"
                title="New Task"
            >
                <i class="bi bi-plus-lg"></i>
            </a>

            <a
                href="{{ route('category.create') }}"
                title="New Category"
            >
                <i class="bi bi-tags"></i>
            </a>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button type="submit" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

        </nav>

    </main>
</div>

@endsection