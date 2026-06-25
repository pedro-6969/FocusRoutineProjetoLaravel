<x-app-layout>
    <div class="app-wrapper">

        {{-- Sidebar desktop --}}
        <aside class="app-sidebar d-none d-lg-block">
            <div class="text-center mb-4">
                <span class="fr-logo fr-logo-dark">
                    <i class="bi bi-bullseye"></i>
                </span>
            </div>

            <a href="{{ route('dashboard') }}" class="sidebar-link active" title="Dashboard">
                <i class="bi bi-house"></i>
            </a>

            <a href="{{ route('task.create') }}" class="sidebar-link" title="New Task">
                <i class="bi bi-plus-square"></i>
            </a>

            <a href="{{ route('category.create') }}" class="sidebar-link" title="New Category">
                <i class="bi bi-tags"></i>
            </a>

            {{-- Use esta rota apenas se o CalendarController já existir --}}
            <a href="{{ route('calendar.index') }}" class="sidebar-link" title="Calendar">
                <i class="bi bi-calendar3"></i>
            </a>
        </aside>

        {{-- Conteúdo principal --}}
        <main class="content-area">

            {{-- Topbar mobile --}}
            <div class="mobile-topbar">
                <div class="fr-brand">
                    <span class="fr-logo">
                        <i class="bi bi-bullseye"></i>
                    </span>
                    <span>Focus Routine</span>
                </div>

                <span>
                    <i class="bi bi-person-circle"></i>
                </span>
            </div>

            <div class="container-fluid">

                {{-- Mensagem de sucesso --}}
                @if (session('success'))
                    <div class="alert alert-success rounded-3">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Mensagem de informação --}}
                @if (session('info'))
                    <div class="alert alert-info rounded-3">
                        {{ session('info') }}
                    </div>
                @endif

                {{-- Mensagens de erro --}}
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <strong>Check the information below:</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Cabeçalho do dashboard --}}
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1">
                            Bem-vindo de volta, {{ Auth::user()->name }}!
                        </h2>

                        <p class="text-muted mb-0">
                            Organize your day and stay focused.
                        </p>
                    </div>

                    <a href="{{ route('task.create') }}" class="btn btn-fr-primary">
                        Add New Task +
                    </a>
                </div>

                {{-- Cards superiores --}}
                <div class="row g-3 mb-4">

                    {{-- Card de boas-vindas --}}
                    <div class="col-12 col-lg-7">
                        <div class="welcome-card h-100">
                            <h4 class="fw-bold mb-2">
                                “Organize your day and stay focused.”
                            </h4>

                            <p class="mb-0">
                                Create categories, manage your tasks and improve your productivity.
                            </p>
                        </div>
                    </div>

                    {{-- Resumo de produtividade --}}
                    <div class="col-12 col-lg-5">
                        <div class="summary-card h-100">
                            <h6 class="fw-bold mb-3">
                                Resumo de Produtividade
                            </h6>

                            <div class="row text-center g-2">
                                <div class="col-3">
                                    <h5 class="fw-bold mb-0">
                                        {{ $total_task }}
                                    </h5>
                                    <small class="text-muted">
                                        Total
                                    </small>
                                </div>

                                <div class="col-3">
                                    <h5 class="fw-bold mb-0">
                                        {{ $pending_task }}
                                    </h5>
                                    <small class="text-muted">
                                        Pending
                                    </small>
                                </div>

                                <div class="col-3">
                                    <h5 class="fw-bold mb-0">
                                        {{ $completed_task }}
                                    </h5>
                                    <small class="text-muted">
                                        Done
                                    </small>
                                </div>

                                <div class="col-3">
                                    <h5 class="fw-bold mb-0 text-danger">
                                        {{ $overdue_task ?? 0 }}
                                    </h5>
                                    <small class="text-muted">
                                        Late
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Área principal: categorias + tarefas --}}
                <div class="row g-4">

                    {{-- Coluna lateral de categorias --}}
                    <div class="col-12 col-lg-3">
                        <div class="fr-card p-3 category-panel">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">
                                    Categories
                                </h5>

                                <a href="{{ route('category.create') }}" class="btn btn-sm btn-fr-primary">
                                    +
                                </a>
                            </div>

                            {{-- Todas as tarefas --}}
                            <a
                                href="{{ route('dashboard', request()->except('category_id', 'page')) }}"
                                class="category-scroll-item {{ request('category_id') ? '' : 'active' }}"
                            >
                                <span>
                                    <i class="bi bi-grid"></i>
                                    All Tasks
                                </span>

                                <span>
                                    {{ $total_task }}
                                </span>
                            </a>

                            {{-- Lista de categorias com scroll --}}
                            <div class="category-scroll mt-2">
                                @forelse ($category as $item)
                                    <div class="category-row">

                                        {{-- 
                                            Ao clicar na categoria, filtramos as tarefas.
                                            Mantemos os outros filtros atuais usando request()->except().
                                        --}}
                                        <a
                                            href="{{ route('dashboard', array_merge(request()->except('page'), ['category_id' => $item->id])) }}"
                                            class="category-scroll-item {{ request('category_id') == $item->id ? 'active' : '' }}"
                                        >
                                            <span>
                                                <span class="category-color-dot category-{{ $item->color }}"></span>
                                                {{ $item->name }}
                                            </span>

                                            <span>
                                                {{ $item->task_count }}
                                            </span>
                                        </a>

                                        {{-- Ações da categoria --}}
                                        <div class="category-actions">
                                            <a href="{{ route('category.edit', $item->id) }}" title="Edit category">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form
                                                action="{{ route('category.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this category?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="Delete category">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted small py-3">
                                        <p class="mb-2">
                                            No categories yet.
                                        </p>

                                        <a href="{{ route('category.create') }}" class="btn btn-sm btn-fr-primary">
                                            Create Category
                                        </a>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                    {{-- Coluna de tarefas --}}
                    <div class="col-12 col-lg-9">

                        {{-- Filtros --}}
                        <div class="fr-card p-3 mb-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="row g-2">

                                {{-- 
                                    Campo oculto para preservar o filtro de categoria.
                                    Sem isso, ao filtrar por prioridade/status/data,
                                    o category_id seria perdido.
                                --}}
                                @if (request('category_id'))
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                @endif

                                <div class="col-12 col-md-3">
                                    <select name="priority" class="form-select">
                                        <option value="">All priorities</option>
                                        <option value="High" @selected(request('priority') === 'High')>
                                            High
                                        </option>
                                        <option value="Medium" @selected(request('priority') === 'Medium')>
                                            Medium
                                        </option>
                                        <option value="Low" @selected(request('priority') === 'Low')>
                                            Low
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All status</option>
                                        <option value="Pending" @selected(request('status') === 'Pending')>
                                            Pending
                                        </option>
                                        <option value="Completed" @selected(request('status') === 'Completed')>
                                            Completed
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <input
                                        type="date"
                                        name="task_date"
                                        class="form-control"
                                        value="{{ request('task_date') }}"
                                    >
                                </div>

                                <div class="col-12 col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-fr-primary w-100">
                                        Filter
                                    </button>

                                    <a href="{{ route('dashboard') }}" class="btn btn-fr-outline">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                </div>

                            </form>
                        </div>

                        {{-- Título da lista --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h4 class="fw-bold mb-0">
                                    Task List
                                </h4>

                                <small class="text-muted">
                                    High priority tasks appear first.
                                </small>
                            </div>

                            <a href="{{ route('task.create') }}" class="btn btn-fr-primary">
                                Add New Task +
                            </a>
                        </div>

                        {{-- Cards de tarefas --}}
                        <div class="row g-3">
                            @forelse ($task as $item)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="task-card">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <small class="text-muted">
                                                    {{ $item->category?->name ?? 'No category' }}
                                                </small>

                                                <h6 class="fw-bold mb-1">
                                                    {{ $item->title }}
                                                </h6>
                                            </div>

                                            <span class="priority-badge {{ $item->priorityClass() }}">
                                                {{ $item->priority }}
                                            </span>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            {{ $item->description ?: 'No description.' }}
                                        </p>

                                        <div class="small text-muted mb-2">
                                            <i class="bi bi-calendar"></i>

                                            {{ \Carbon\Carbon::parse($item->task_date)->format('d/m/Y') }}

                                            @if ($item->task_time)
                                                - {{ \Carbon\Carbon::parse($item->task_time)->format('H:i') }}
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="{{ $item->status === 'Completed' ? 'status-completed' : 'status-pending' }}">
                                                {{ $item->status }}
                                            </span>

                                            <div class="d-flex gap-2">

                                                {{-- Botão de concluir --}}
                                                @if ($item->status === 'Pending')
                                                    <form action="{{ route('task.complete', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button class="btn btn-sm btn-fr-outline" type="submit" title="Complete task">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Botão de editar --}}
                                                <a href="{{ route('task.edit', $item->id) }}" class="btn btn-sm btn-fr-outline" title="Edit task">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                {{-- Botão de excluir --}}
                                                <form
                                                    action="{{ route('task.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this task?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete task">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="fr-card p-4 text-center">
                                        <h5 class="fw-bold">
                                            No tasks found
                                        </h5>

                                        <p class="text-muted">
                                            Create a task or change the filters.
                                        </p>

                                        <a href="{{ route('task.create') }}" class="btn btn-fr-primary">
                                            Create Task
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- Paginação --}}
                        @if ($task->hasPages())
                            <div class="mt-4">
                                {{ $task->links() }}
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{-- Bottom navigation mobile --}}
            <nav class="bottom-nav">
                <a href="{{ route('dashboard') }}" class="active">
                    <i class="bi bi-house"></i>
                </a>

                <a href="{{ route('task.create') }}">
                    <i class="bi bi-plus-square"></i>
                </a>

                <a href="{{ route('category.create') }}">
                    <i class="bi bi-tags"></i>
                </a>

                {{-- Use somente se a rota do calendário já existir --}}
                <a href="{{ route('calendar.index') }}">
                    <i class="bi bi-calendar3"></i>
                </a>
            </nav>

        </main>
    </div>
</x-app-layout>