<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Transaction Detail ({{ $transaction->type ? $transaction->type == 'sale' ? 'Penjualan' : 'Pembelian' : '' }})
        </h2>
    </x-slot>

    <div class="py-6 flex justify-center">
        <div class="max-w-6xl w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER INFO --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    @php
                        $label = 'text-gray-500 dark:text-gray-400';
                        $value = 'font-semibold text-gray-900 dark:text-gray-100';
                    @endphp

                    <div>
                        <div class="{{ $label }}">Transaction Code</div>
                        <div class="{{ $value }}">
                            TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div>
                        <div class="{{ $label }}">Type</div>
                        <div class="{{ $value }} capitalize">
                            {{ $transaction->type }} / {{ $transaction->type ? $transaction->type == 'sale' ? 'Penjualan' : 'Pembelian' : '' }}
                        </div>
                    </div>

                    <div>
                        <div class="{{ $label }}">Date</div>
                        <div class="{{ $value }}">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="{{ $label }}">Partner</div>
                        <div class="{{ $value }}">
                            {{ $transaction->partner->name }}
                        </div>
                    </div>

                    <div>
                        <div class="{{ $label }}">Created By</div>
                        <div class="{{ $value }}">
                            {{ $transaction->creator->name }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEMS TABLE --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm text-gray-800 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-right">Price</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                            <th class="px-4 py-3 text-right">Discount</th>
                            <th class="px-4 py-3 text-right">Tax</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($transaction->items as $item)
                            <tr class="bg-gray-800 dark:bg-gray-900 dark:hover:bg-black p-4 rounded shadow">
                                <td class="px-4 py-3">
                                    <div class="font-medium">
                                        {{ $item->product->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->tax_code ?? '-' }}
                                        @if($item->tax_rate)
                                            ({{ $item->tax_rate }}%)
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    {{ number_format($item->price, 2) }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $item->qty }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    {{ number_format($item->subtotal, 2) }}
                                </td>

                                <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">
                                    {{ number_format($item->discount_amount ?? 0, 2) }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    {{ number_format($item->tax_amount ?? 0, 2) }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ number_format($item->total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- SUMMARY --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 flex justify-end">
                <table class="text-sm w-full md:w-1/3 text-gray-800 dark:text-gray-200">
                    <tr>
                        <td class="py-1">Subtotal</td>
                        <td class="py-1 text-right">
                            {{ number_format($transaction->items->sum('subtotal'), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">Discount</td>
                        <td class="py-1 text-right text-red-600 dark:text-red-400">
                            {{ number_format($transaction->items->sum('discount_amount'), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">Tax</td>
                        <td class="py-1 text-right">
                            {{ number_format($transaction->items->sum('tax_amount'), 2) }}
                        </td>
                    </tr>
                    <tr class="font-semibold border-t border-gray-200 dark:border-gray-700">
                        <td class="py-2">Grand Total</td>
                        <td class="py-2 text-right">
                            {{ number_format($transaction->items->sum('total'), 2) }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end">
                <a href="{{ route('transactions.index', ['type' => $transaction->type]) }}"
                   class="px-4 py-2 rounded
                          bg-gray-200 text-gray-800
                          dark:bg-gray-700 dark:text-gray-200
                          hover:bg-gray-300 dark:hover:bg-gray-600">
                    Back
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
