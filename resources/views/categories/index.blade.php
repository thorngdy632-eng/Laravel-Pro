<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Categories') }}
            </h2>

            <a href="{{ route('categories.create') }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                {{ __('Create New Category') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message :message="session('success')" />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($categories as $category)
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 py-4 last:border-0 last:pb-0">
                            <div>
                                <a href="{{ route('categories.show', $category) }}"
                                    class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $category->name }}
                                </a>

                                <p class="text-sm text-gray-500">
                                    {{ __(':count posts', ['count' => $category->posts_count]) }}
                                </p>
                            </div>

                            <div class="inline-flex items-center gap-4 text-sm font-medium">
                                <a href="{{ route('categories.posts.index', $category) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('View Posts') }}
                                </a>

                                <a href="{{ route('categories.edit', $category) }}" class="text-amber-600 hover:text-amber-900">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                            {{ __('No categories found.') }}
                            <a href="{{ route('categories.create') }}" class="text-indigo-600 underline">
                                {{ __('Create one now') }}
                            </a>
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>