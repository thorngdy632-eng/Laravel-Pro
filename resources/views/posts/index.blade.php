<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('categories.index') }}" class="text-decoration-none">
                                {{ __('Categories') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $category->name }}
                        </li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold text-dark mb-0">
                    {{ __('Posts') }}: <span class="text-muted fw-normal">{{ $category->name }}</span>
                </h2>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('categories.posts.create', $category) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Create New Post') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            
            {{-- Flash Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Search Form Card --}}
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-body">
                    <form method="GET" action="{{ route('categories.posts.index', $category) }}" class="row g-3 align-items-center">
                        <div class="col-md-8 col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                       placeholder="{{ __('Search posts by title...') }}"
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-1">
                                {{ __('Search') }}
                            </button>
                            <a href="{{ route('categories.posts.index', $category) }}" class="btn btn-outline-secondary">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results Count --}}
            @if ($posts->total() > 0)
                <p class="text-muted small mb-3">
                    {{ __('Showing') }} <span class="fw-semibold">{{ $posts->firstItem() }}</span>–<span class="fw-semibold">{{ $posts->lastItem() }}</span>
                    {{ __('of') }} <span class="fw-semibold">{{ $posts->total() }}</span> {{ __('posts') }}
                </p>
            @endif

            {{-- Posts List Card --}}
            <div class="card shadow-sm border-0">
                <div class="list-group list-group-flush">
                    @forelse ($posts as $post)
                        <div class="list-group-item p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            
                            {{-- Left Side: Image, Title, Content, Author --}}
                            <div class="d-flex align-items-start gap-3">
                                @if ($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" 
                                         class="rounded object-fit-cover border flex-shrink-0" 
                                         style="width: 70px; height: 70px;" 
                                         alt="{{ $post->title }}">
                                @else
                                    <div class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 70px; height: 70px;">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                @endif

                                <div>
                                    <h5 class="mb-1">
                                        <a href="{{ route('categories.posts.show', [$category, $post]) }}"
                                           class="text-decoration-none text-dark fw-semibold">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        {{ Str::limit($post->content, 90) }}
                                    </p>
                                    <span class="text-muted text-xs" style="font-size: 0.75rem;">
                                        <i class="bi bi-person me-1"></i> By {{ $post->user?->name ?? __('Unknown') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Right Side: Actions --}}
                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0 flex-shrink-0">
                                <a href="{{ route('categories.posts.show', [$category, $post]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    {{ __('View') }}
                                </a>

                                <a href="{{ route('categories.posts.edit', [$category, $post]) }}"
                                    class="btn btn-sm btn-outline-warning text-dark">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('categories.posts.destroy', [$category, $post]) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this post?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="text-muted mb-2">
                                <i class="bi bi-journal-x fs-1"></i>
                            </div>
                            <p class="text-muted mb-2">{{ __('No posts match your criteria or exist in this category yet.') }}</p>
                            <a href="{{ route('categories.posts.create', $category) }}"
                               class="btn btn-sm btn-primary mt-2">
                                {{ __('Create one now') }}
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>