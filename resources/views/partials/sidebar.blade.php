<aside class="app-sidebar d-none d-lg-flex flex-column">

    <div class="text-center mb-4">
        <span class="fr-logo fr-logo-dark mx-auto">
            <i class="bi bi-bullseye"></i>
        </span>
    </div>

    <nav class="sidebar-nav flex-grow-1">

        <a
            href="{{ route('dashboard') }}"
            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            title="Dashboard"
        >
            <i class="bi bi-house"></i>
        </a>

        <a
            href="{{ route('task.create') }}"
            class="sidebar-link {{ request()->routeIs('task.create') ? 'active' : '' }}"
            title="New Task"
        >
            <i class="bi bi-plus-square"></i>
        </a>

        <a
            href="{{ route('category.create') }}"
            class="sidebar-link {{ request()->routeIs('category.create') ? 'active' : '' }}"
            title="New Category"
        >
            <i class="bi bi-tags"></i>
        </a>

        <a
            href="{{ route('calendar.show') }}"
            class="sidebar-link {{ request()->routeIs('calendar.show') ? 'active' : '' }}"
            title="Calendar"
        >
            <i class="bi bi-calendar3"></i>
        </a>

    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf

        <button
            type="submit"
            class="sidebar-link border-0 bg-transparent"
            title="Logout"
        >
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>

</aside>