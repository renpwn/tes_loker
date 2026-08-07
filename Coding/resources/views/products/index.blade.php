<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Products
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between mb-4">
            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                    Add Product
                </a>
            @endcan
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <table id="products-table" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="uppercase bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th width="150">Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
              href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            function formatNumber(data) {
                if (data === null || data === undefined) return '0';
                // Gunakan locale Indonesia (ID) untuk format mata uang/angka
                return parseFloat(data).toLocaleString('id-ID', {
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2
                });
            }

            function formatStock(data) {
                if (data === null || data === undefined) return '0';
                // Stok biasanya bilangan bulat, tetapi kita gunakan format ribuan
                return parseFloat(data).toLocaleString('id-ID', {
                    minimumFractionDigits: 0, 
                    maximumFractionDigits: 0
                });
            }

            $(function () {
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('products.index') }}",
                    columns: [
                        { data: 'sku', name: 'sku' },
                        { data: 'name', name: 'name' },
                        { data: 'category', name: 'category.name' },
                        
                        // --- PRICE (Harga) ---
                        { 
                            data: 'price', 
                            name: 'price', 
                            className: 'text-right', // Agar harga rata kanan
                            render: function (data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    return formatNumber(data);
                                }
                                return data; // Kembalikan nilai mentah untuk sorting
                            }
                        },
                        
                        // --- STOCK (Stok) ---
                        { 
                            data: 'stock', 
                            name: 'stock', 
                            className: 'text-center', // Rata tengah
                            searchable: true, // Diubah menjadi true
                            render: function (data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    return formatStock(data);
                                }
                                return data; // Kembalikan nilai mentah untuk sorting
                            }
                        },
                        { data: 'actions', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
