<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            @include('products.form', [
                'submit' => 'Update'
            ])
        </form>
    </div>
</x-app-layout>
