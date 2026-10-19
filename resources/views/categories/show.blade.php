<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $category->name }}
            </h2>

            <div class="flex items-center gap-4">
                <a href="{{ route('categories.posts.create', $category) }}"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    {{ __('Create New Post') }}
                </a>

                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    {{ __('Back to Categories') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message :message="session('success')" />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Posts') }}
                            <span class="text-sm font-normal text-gray-500">({{ $posts->count() }})</span>
                        </h3>

                        <a href="{{ route('categories.posts.index', $category) }}"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                            {{ __('View all') }}
                        </a>
                    </div>

                    @forelse ($posts as $post)
                        <div
                            class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 py-4 last:border-0 last:pb-0">
                            <div class="min-w-0">
                                <a href="{{ route('categories.posts.show', [$category, $post]) }}"
                                    class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $post->title }}
                                </a>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ Str::limit($post->content, 150) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    {{ __('By :author', ['author' => $post->user?->name ?? __('Unknown')]) }}
                                </p>
                            </div>

                            <div class="inline-flex shrink-0 items-center gap-4 text-sm font-medium">
                                <a href="{{ route('categories.posts.edit', [$category, $post]) }}"
                                    class="text-amber-600 hover:text-amber-900">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('categories.posts.destroy', [$category, $post]) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-gray-500">
                            {{ __('No posts in this category yet.') }}
                            <a href="{{ route('categories.posts.create', $category) }}" class="text-indigo-600 underline">
                                {{ __('Create one now') }}
                            </a>
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>