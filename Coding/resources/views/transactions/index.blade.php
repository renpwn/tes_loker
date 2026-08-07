<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Transactions {{ request('type') ? ucfirst(request('type')) . 's' : '' }} / {{ request('type') ? request('type') == 'sale' ? 'Penjualan' : 'Pembelian' : '' }}
        </h2>
    </x-slot>

    <div class="py-6 flex justify-center">
        <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8">

            {{-- Action --}}
            <div class="flex justify-between mb-4">
                @can('create', App\Models\Transaction::class)
                    <a href="{{ route('transactions.create', ['type' => request('type')]) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                        + New {{ request('type') ? ucfirst(request('type')) : 'Transaction' }}
                    </a>
                @endcan
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                <table id="transactions-table"
                       class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase">Code</th>
                            <!-- <th class="px-4 py-2 text-left text-xs font-medium uppercase">Type</th> -->
                            <th class="px-4 py-2 text-right text-xs font-medium uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase">User</th>
                            <th class="px-4 py-2 text-center text-xs font-medium uppercase">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

    @push('scripts')
        {{-- DataTables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(function () {
                $('#transactions-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('transactions.index') }}",
                        data: function (d) {
                            d.type = "{{ request('type') }}";
                        }
                    },
                    order: [[0, 'desc']],
                    columns: [
                        { data: 'date', name: 'date' },
                        { data: 'code', name: 'code' },
                        // { data: 'type', name: 'type' },
                        {
                            data: 'total',
                            name: 'total',
                            className: 'text-right'
                        },
                        { data: 'creator.name', name: 'creator.name' },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
