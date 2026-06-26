@extends('layouts.app')

@section('title', 'Focus Routine - Criar Categoria')

@section('content')
    <div class="app-wrapper">

        {{-- =========================================================
            SIDEBAR — VISÍVEL SOMENTE NO DESKTOP
        ========================================================== --}}
        @include('partials.sidebar')

        {{-- =========================================================
            CONTEÚDO PRINCIPAL
        ========================================================== --}}
        <main class="content-area">

            {{-- Topbar mobile --}}
            @include('partials.mobile-topbar')

            <div class="container-fluid">

                {{-- =================================================
                    CABEÇALHO DA PÁGINA
                ================================================== --}}
                <div
                    class="d-flex justify-content-between align-items-center
                           flex-wrap gap-3 mb-4"
                >
                    <div>
                        <h2 class="fw-bold mb-1">
                            Create Category
                        </h2>

                        <p class="text-muted mb-0">
                            Create a category to organize your routine tasks.
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
                    MENSAGEM INFORMATIVA

                    Aparece, por exemplo, quando o usuário tenta criar
                    uma tarefa sem possuir nenhuma categoria.
                ================================================== --}}
                @if (session('info'))
                    <div
                        class="alert alert-info rounded-3"
                        role="alert"
                    >
                        <i class="bi bi-info-circle me-1"></i>
                        {{ session('info') }}
                    </div>
                @endif

                {{-- Mensagem de sucesso --}}
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
                    ERROS DE VALIDAÇÃO
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
                    FORMULÁRIO
                ================================================== --}}
                <div class="row justify-content-center">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">

                        <div class="fr-card p-4 p-md-5">

                            {{-- Identidade do aplicativo --}}
                            <div class="text-center mb-4">

                                <div
                                    class="fr-brand fr-brand-dark
                                           justify-content-center mb-3"
                                >
                                    <span class="fr-logo fr-logo-dark">
                                        <i class="bi bi-tags"></i>
                                    </span>

                                    <span>Focus Routine</span>
                                </div>

                                <h3 class="fw-bold mb-2">
                                    Create New Category
                                </h3>

                                <p class="text-muted mb-0">
                                    Categories help you separate tasks by
                                    study, work, health or personal activities.
                                </p>

                            </div>

                            <form
                                action="{{ route('category.store') }}"
                                method="POST"
                            >
                                @csrf

                                {{-- =====================================
                                    NOME DA CATEGORIA
                                ====================================== --}}
                                <div class="mb-4">

                                    <label
                                        for="name"
                                        class="form-label fw-semibold"
                                    >
                                        Category Name
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control
                                               @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Example: Study, Work or Gym"
                                        maxlength="60"
                                        autocomplete="off"
                                        autofocus
                                        required
                                    >

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @else
                                        <div class="form-text">
                                            The name must be unique among your
                                            categories.
                                        </div>
                                    @enderror

                                </div>

                                {{-- =====================================
                                    ESCOLHA DA COR

                                    Os valores precisam ser iguais aos
                                    permitidos no CategoryController:
                                    blue, green, yellow, red, purple e gray.
                                ====================================== --}}
                                <div class="mb-4">

                                    <label class="form-label fw-semibold">
                                        Category Color
                                    </label>

                                    <p class="text-muted small mb-3">
                                        Choose a color to identify this category
                                        on the dashboard.
                                    </p>

                                    @php
                                        $colors = [
                                            'blue' => 'Blue',
                                            'green' => 'Green',
                                            'yellow' => 'Yellow',
                                            'red' => 'Red',
                                            'purple' => 'Purple',
                                            'gray' => 'Gray',
                                        ];
                                    @endphp

                                    <div class="row g-2">

                                        @foreach ($colors as $value => $label)
                                            <div class="col-6 col-sm-4">

                                                {{-- Radio escondido pelo Bootstrap --}}
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="color"
                                                    id="color-{{ $value }}"
                                                    value="{{ $value }}"
                                                    autocomplete="off"
                                                    @checked(old('color', 'blue') === $value)
                                                >

                                                {{-- Card visual da opção --}}
                                                <label
                                                    for="color-{{ $value }}"
                                                    class="category-option w-100 h-100"
                                                >
                                                    <span
                                                        class="category-color-dot
                                                               category-{{ $value }}"
                                                    ></span>

                                                    <span class="d-block mt-2">
                                                        {{ $label }}
                                                    </span>
                                                </label>

                                            </div>
                                        @endforeach

                                    </div>

                                    @error('color')
                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- =====================================
                                    EXPLICAÇÃO DA LÓGICA
                                ====================================== --}}
                                <div class="alert alert-light border rounded-3 mb-4">

                                    <div class="d-flex gap-2">
                                        <i
                                            class="bi bi-lightbulb
                                                   text-warning"
                                        ></i>

                                        <div>
                                            <strong>How categories work</strong>

                                            <p class="small text-muted mb-0 mt-1">
                                                After creating the category,
                                                it will appear in the category
                                                panel on your dashboard and can
                                                be selected when creating a task.
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
                                    >
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create Category
                                    </button>

                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="btn btn-fr-outline py-2"
                                    >
                                        Cancel
                                    </a>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>

            </div>

            {{-- =========================================================
                NAVEGAÇÃO MOBILE
            ========================================================== --}}
            @include('partials.bottom-nav')

        </main>
    </div>
@endsection