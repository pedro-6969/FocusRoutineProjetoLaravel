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

            {{-- Link para mostrar todas as tarefas --}}
            <a
                href="{{ route('dashboard') }}"
                class="category-scroll-item {{ request('category_id') ? '' : 'active' }}"
            >
                <span>All Tasks</span>
                <span>{{ $total_task }}</span>
            </a>

            {{-- Lista de categorias com scroll --}}
            <div class="category-scroll">
                @forelse ($category as $item)
                    <div class="category-row">

                        {{-- Ao clicar na categoria, filtra as tarefas --}}
                        <a
                            href="{{ route('dashboard', ['category_id' => $item->id]) }}"
                            class="category-scroll-item {{ request('category_id') == $item->id ? 'active' : '' }}"
                        >
                            <span>
                                <span class="category-color-dot category-{{ $item->color }}"></span>
                                {{ $item->name }}
                            </span>

                            <span>{{ $item->task_count }}</span>
                        </a>

                        {{-- Ações da categoria --}}
                        <div class="category-actions">
                            <a href="{{ route('category.edit', $item->id) }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('category.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted small py-3">
                        No categories yet.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Lista de tarefas --}}
    <div class="col-12 col-lg-9">

        {{-- Filtros --}}
        <div class="fr-card p-3 mb-3">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-2">

                <div class="col-12 col-md-3">
                    <select name="priority" class="form-select">
                        <option value="">All priorities</option>
                        <option value="High" @selected(request('priority') === 'High')>High</option>
                        <option value="Medium" @selected(request('priority') === 'Medium')>Medium</option>
                        <option value="Low" @selected(request('priority') === 'Low')>Low</option>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All status</option>
                        <option value="Pending" @selected(request('status') === 'Pending')>Pending</option>
                        <option value="Completed" @selected(request('status') === 'Completed')>Completed</option>
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

                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-fr-primary w-100">
                        Filter
                    </button>
                </div>

            </form>
        </div>

        {{-- Botão nova tarefa --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">
                Task List
            </h4>

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
                                    {{ $item->category?->name }}
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
                            {{ $item->description ?? 'No description.' }}
                        </p>

                        <div class="small text-muted mb-2">
                            <i class="bi bi-calendar"></i>
                            {{ \Carbon\Carbon::parse($item->task_date)->format('d/m/Y') }}

                            @if ($item->task_time)
                                - {{ $item->task_time }}
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="{{ $item->status === 'Completed' ? 'status-completed' : 'status-pending' }}">
                                {{ $item->status }}
                            </span>

                            <div class="d-flex gap-2">

                                @if ($item->status === 'Pending')
                                    <form action="{{ route('task.complete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-sm btn-fr-outline" type="submit">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('task.edit', $item->id) }}" class="btn btn-sm btn-fr-outline">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('task.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger" type="submit">
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
                        <h5 class="fw-bold">No tasks found</h5>
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
        <div class="mt-4">
            {{ $task->links() }}
        </div>

    </div>
</div>