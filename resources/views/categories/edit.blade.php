<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Edit Category') }}
            </h2>

            <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                {{ __('Back to Categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-error-message :messages="$errors->all()" />

                    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />

                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $category->name)" required autofocus />

                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Update Category') }}
                            </x-primary-button>

                            <a href="{{ route('categories.index') }}"
                                class="rounded-md bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>