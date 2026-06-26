<nav class="bottom-nav">

    <a
        href="{{ route('dashboard') }}"
        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
        title="Dashboard"
    >
        <i class="bi bi-house"></i>
    </a>

    <a
        href="{{ route('task.create') }}"
        class="{{ request()->routeIs('task.create') ? 'active' : '' }}"
        title="New Task"
    >
        <i class="bi bi-plus-square"></i>
    </a>

    <a
        href="{{ route('category.create') }}"
        class="{{ request()->routeIs('category.create') ? 'active' : '' }}"
        title="New Category"
    >
        <i class="bi bi-tags"></i>
    </a>

    <a
        href="{{ route('calendar.show') }}"
        class="{{ request()->routeIs('calendar.show') ? 'active' : '' }}"
        title="Calendar"
    >
        <i class="bi bi-calendar3"></i>
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>

</nav>