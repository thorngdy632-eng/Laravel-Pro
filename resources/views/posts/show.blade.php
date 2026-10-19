<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $post->title }}
            </h2>

            <div class="flex items-center gap-4">
                <a href="{{ route('categories.posts.edit', [$category, $post]) }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                    {{ __('Edit') }}
                </a>

                <a href="{{ route('categories.posts.index', $category) }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    {{ __('Back to Posts') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-6 text-gray-900">
                    <x-flash-message :message="session('success')" />

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">
                                {{ __('Category') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('categories.show', $category) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    {{ $category->name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">
                                {{ __('Author') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $post->user?->name ?? __('Unknown') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">
                                {{ __('Created') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $post->created_at?->format('M j, Y') }}
                            </dd>
                        </div>
                    </dl>

                    <div class="border-t border-gray-200 pt-6 text-sm leading-6 text-gray-700">
                        <p class="whitespace-pre-line">{{ $post->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>