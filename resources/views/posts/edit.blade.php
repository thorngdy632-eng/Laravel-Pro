<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Edit Post in :category', ['category' => $category->name]) }}
            </h2>

            <a href="{{ route('categories.posts.index', $category) }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900">
                {{ __('Back to Posts') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-error-message :messages="$errors->all()" />

                    <form action="{{ route('categories.posts.update', [$category, $post]) }}" method="POST"
                        class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title Field -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />

                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title', $post->title)" required autofocus />

                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Current & New Image Section -->
                        <div>
                            <x-input-label :value="__('Featured Image')" />

                            @if ($post->image)
                                <div class="my-2">
                                    <p class="text-xs text-gray-500 mb-1">{{ __('Current Image:') }}</p>
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                        class="h-24 w-auto rounded-md object-cover border">
                                </div>
                            @endif

                            <label class="block text-xs font-medium text-gray-600 mt-2 mb-1">
                                {{ __('Replace Image (optional)') }}
                            </label>
                            
                            <input id="image" type="file" name="image" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-md file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-indigo-50 file:text-indigo-600
                                       hover:file:bg-indigo-100 cursor-pointer">

                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Content Field -->
                        <div>
                            <x-input-label for="content" :value="__('Content')" />

                            <textarea id="content" name="content" rows="6" required
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3">{{ old('content', $post->content) }}</textarea>

                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Form Buttons -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Update Post') }}
                            </x-primary-button>

                            <a href="{{ route('categories.posts.index', $category) }}"
                                class="inline-flex items-center rounded-md bg-gray-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 transition">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>