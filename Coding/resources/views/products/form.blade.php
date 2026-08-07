<div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4 mx-auto mt-6">
    {{-- Form tag dan action diasumsikan berada di luar blok ini atau Anda akan menambahkannya --}}
    {{-- <form method="POST" action="..."> --}}
    
    <div class="space-y-4"> {{-- Gunakan space-y-4 untuk jarak antar field, atau mb-4 seperti Form B --}}

        {{-- CATEGORY --}}
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="category_id">
                Category
            </label>
            <select name="category_id" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        @selected(old('category_id', $product?->category_id) == $cat->id)>
                        {{ $cat->name }} ({{ $cat->is_service ? 'Service' : 'Goods' }})
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- SKU --}}
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="sku">
                SKU
            </label>
            <input name="sku" type="text" value="{{ old('sku', $product->sku) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- NAME --}}
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="name">
                Name
            </label>
            <input name="name" type="text" value="{{ old('name', $product->name) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- PRICE --}}
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="price">
                Price
            </label>
            <input name="price" type="number" value="{{ old('price', $product->price) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- INITIAL STOCK --}}
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="initial_stock">
                Initial Stock
            </label>
            <input name="initial_stock" type="number" value="{{ old('initial_stock', $product->initial_stock) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('initial_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- SUBMIT --}}
        <div class="flex items-center justify-between pt-4">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ $submit }}
            </button>
        </div>
    </div>
    
    {{-- </form> --}}
</div>