<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Add Category
        </h2>
    </x-slot>

    @include('categories.form')
</x-app-layout>
