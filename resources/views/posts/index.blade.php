<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('categories.index') }}" class="hover:text-gray-700 hover:underline">
                        {{ __('Categories') }}
                    </a>
                    <span class="mx-1">/</span>
                    {{ $category->name }}
                </p>

                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Posts') }}
                </h2>
            </div>

            <a href="{{ route('categories.posts.create', $category) }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                {{ __('Create New Post') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-flash-message :message="session('success')" />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ __('Title') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ __('Author') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($posts as $post)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('categories.posts.show', [$category, $post]) }}"
                                            class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $post->title }}
                                        </a>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ Str::limit($post->content, 100) }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $post->user?->name ?? __('Unknown') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="inline-flex items-center space-x-4">
                                            <a href="{{ route('categories.posts.show', [$category, $post]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ __('View') }}
                                            </a>

                                            <a href="{{ route('categories.posts.edit', [$category, $post]) }}"
                                                class="text-amber-600 hover:text-amber-900">
                                                {{ __('Edit') }}
                                            </a>

                                            <form action="{{ route('categories.posts.destroy', [$category, $post]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('No posts in this category yet.') }}
                                        <a href="{{ route('categories.posts.create', $category) }}"
                                            class="text-indigo-600 underline">
                                            {{ __('Create one now') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>