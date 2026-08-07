<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Categories
        </h2>
    </x-slot>

    <div class="py-6 flex justify-center">
        <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between mb-4">
                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}"
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                        Add Category
                    </a>
                @endcan
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                <table id="categories-table"
                       class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Is Service</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(function () {
                console.log("Initializing DataTable for Categories");
                $('#categories-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('categories.index') }}",
                    columns: [
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { 
                            data: 'is_service', // Ini adalah data 0 atau 1 (untuk sorting)
                            name: 'is_service',
                            render: function (data, type, row) {
                                // Ini adalah Tampilan (untuk display)
                                return data == 1 ? 'Yes' : 'No';
                            }
                        },
                        { data: 'actions', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
