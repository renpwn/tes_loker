<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Product
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            @include('products.form', [
                'submit' => 'Save'
            ])
        </form>
    </div>
</x-app-layout>
