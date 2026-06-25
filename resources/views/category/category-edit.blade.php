<x-app-layout>
    <div class="app-wrapper">
        <main class="content-area w-100">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">

                        <div class="fr-card p-4">

                            <div class="text-center mb-4">
                                <div class="fr-brand fr-brand-dark justify-content-center mb-2">
                                    <span class="fr-logo fr-logo-dark">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                    <span>Focus Routine</span>
                                </div>

                                <h3 class="fw-bold mb-1">
                                    Edit Category
                                </h3>

                                <p class="text-muted mb-0">
                                    Update your category name and color.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('category.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Category Name
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', $category->name) }}"
                                        required
                                    >
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Category Color
                                    </label>

                                    <div class="row g-2">
                                        @foreach (['blue', 'green', 'yellow', 'red', 'purple', 'gray'] as $color)
                                            <div class="col-6 col-sm-4">
                                                <input
                                                    type="radio"
                                                    class="btn-check"
                                                    name="color"
                                                    id="color-{{ $color }}"
                                                    value="{{ $color }}"
                                                    @checked(old('color', $category->color) === $color)
                                                >

                                                <label class="category-option w-100" for="color-{{ $color }}">
                                                    <div>{{ ucfirst($color) }}</div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-fr-primary py-2">
                                        Save Changes
                                    </button>

                                    <a href="{{ route('category.index') }}" class="btn btn-fr-outline py-2">
                                        Back to Categories
                                    </a>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>