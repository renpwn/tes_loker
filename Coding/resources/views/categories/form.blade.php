@props(['category' => null])

<div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4 mx-auto mt-6">
    <form method="POST" action="{{ $category ? route('categories.update', $category) : route('categories.store') }}">
        @csrf
        @if($category)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="code">
                Code
            </label>
            <input name="code" type="text" value="{{ old('code', $category->code ?? '') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="name">
                Name
            </label>
            <input name="name" type="text" value="{{ old('name', $category->name ?? '') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="is_service" value="1" class="form-checkbox"
                    {{ old('is_service', $category->is_service ?? false) ? 'checked' : '' }}>
                <span class="ml-2">Is Service?</span>
            </label>
            @error('is_service') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ $category ? 'Update' : 'Create' }}
            </button>
        </div>
    </form>
</div>
