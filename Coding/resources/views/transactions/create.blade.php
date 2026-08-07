<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create {{ request('type') ? ucfirst(request('type')) : 'Transaction' }} / {{ request('type') ? request('type') == 'sale' ? 'Penjualan' : 'Pembelian' : '' }}
        </h2>
    </x-slot>

    <div class="py-6 flex justify-center">
        <div class="max-w-6xl w-full px-4 sm:px-6 lg:px-8">

            <form action="{{ route('transactions.store') }}" method="POST"
                  class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-6">
                @csrf

                {{-- TRANSACTION HEADER (TIDAK BERUBAH) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium dark:text-gray-200">Type</label>
                        @php
                            $currentType = request('type'); 
                            $isReadOnly = $currentType ? 'disabled' : ''; 
                        @endphp
                        <select name="type" required {{ $isReadOnly }}
                            class="mt-1 w-full rounded-md 
                                   bg-gray-100 dark:bg-gray-700 
                                   text-gray-800 dark:text-gray-200 
                                   cursor-not-allowed border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            @if ($currentType)
                                <option value="{{ $currentType }}" selected>
                                    {{ ucfirst($currentType) }}
                                </option>
                            @else
                                @foreach($types as $type)
                                    <option value="{{ $type }}">
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if ($currentType)
                            <input type="hidden" name="type" value="{{ $currentType }}">
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium dark:text-gray-200">Partner</label>
                        <select name="partner_id" required
                                class="mt-1 w-full rounded-md 
                                       text-gray-800 dark:text-gray-200 dark:bg-gray-900 
                                       border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select --</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}"
                                        class="dark:bg-gray-800 dark:text-gray-200"> {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium dark:text-gray-200">Date</label>
                        <div class="relative mt-1">
                            <input type="date" name="transaction_date"
                                value="{{ now()->toDateString() }}"
                                class="w-full rounded-md pr-10 
                                         text-gray-800 dark:text-gray-200 dark:bg-gray-900 
                                         border-gray-300 dark:border-gray-600 
                                         focus:ring-blue-500 focus:border-blue-500 
                                         appearance-none" required>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEMS (TIDAK BERUBAH) --}}
                <div class="dark:text-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Items</h3>
                        <button type="button"
                                id="add-row"
                                class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            + Add Item
                        </button>
                    </div>

                    <table class="min-w-full text-sm border dark:border-gray-700" id="items-table">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="p-2 text-left">Product</th>
                            <th class="p-2 text-right">Price</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-right">Subtotal</th>
                            <th class="p-2 text-center"></th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-300"></tbody>
                    </table>
                </div>

                {{-- INPUTS DISKON/PAJAK DAN RINGKASAN TOTAL (NEW STRUCTURE) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 dark:text-gray-200">

                    {{-- KOLOM KIRI (INPUTS) - 2 Kolom di desktop, 1 di mobile --}}
                    <div class="md:col-span-2 space-y-4">
                        {{-- DISCOUNT --}}
                        <div>
                            <label class="block text-sm font-medium">
                                Discount <span class="text-xs text-gray-400 dark:text-gray-500">(10% / 50000)</span>
                            </label>
                            <input type="text"
                                id="discount"
                                name="discount_input"
                                placeholder="0"
                                class="mt-1 w-full rounded 
                                        dark:bg-gray-900 dark:text-gray-200
                                        border-gray-300 dark:border-gray-600">
                        </div>
                        
                        {{-- PPN / PPH --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- PPN --}}
                            <div>
                                <label class="block text-sm font-medium">PPN (%)</label>
                                <input type="number" step="0.01" min="0"
                                    id="ppn_rate"
                                    value="11"
                                    class="mt-1 w-full rounded 
                                            dark:bg-gray-900 dark:text-gray-200
                                            border-gray-300 dark:border-gray-600">
                            </div>
                            {{-- PPH --}}
                            <div>
                                <label class="block text-sm font-medium">PPh</label>
                                <select id="pph_rate"
                                        class="mt-1 w-full rounded 
                                                dark:bg-gray-900 dark:text-gray-200
                                                border-gray-300 dark:border-gray-600">
                                    <option value="0" class="dark:bg-gray-800">None</option>
                                    <option value="2" class="dark:bg-gray-800">PPh 23 (2%)</option>
                                    <option value="5" class="dark:bg-gray-800">PPh 21 (5%)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN (RINGKASAN TOTAL) - 2 Kolom di desktop, menyatu di mobile --}}
                    <div class="md:col-span-2 space-y-1 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-700">
                        
                        <div class="flex justify-between">
                            <span>Sub Total</span>
                            <span id="sub-total-display">0.00</span>
                        </div>

                        <div class="flex justify-between text-red-600 dark:text-red-400">
                            <span>Discount Amount</span>
                            <span id="discount-amount-display">0.00</span>
                        </div>

                        <div class="flex justify-between text-green-600 dark:text-green-400">
                            <span>Total Tax (PPN/PPh)</span>
                            <span id="total-tax-display">0.00</span>
                        </div>

                        <div class="h-px bg-gray-300 dark:bg-gray-600 my-2"></div> {{-- Divider --}}

                        <div class="flex justify-between font-bold text-xl">
                            <span>GRAND TOTAL</span>
                            <span id="grand-total-display">0.00</span>
                        </div>
                    </div>
                </div>

                {{-- HIDDEN INPUTS (TIDAK BERUBAH) --}}
                <div class="hidden"> 
                    <input type="hidden" name="total_amount" id="total-amount">
                    <input type="hidden" name="total_tax" id="total-tax">
                    <input type="hidden" name="discount_type" id="discount-type">
                    <input type="hidden" name="discount_value" id="discount-value">
                    <input type="hidden" name="discount_amount" id="discount-amount">
                    <input type="hidden" name="grand_total" id="grand-total-input">
                </div>

                {{-- ACTION (TIDAK BERUBAH) --}}
                <div class="flex justify-end gap-2">
                    <a href="{{ route('transactions.index') }}"
                       class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- TEMPLATE ROW (TIDAK BERUBAH) --}}
    <template id="row-template">
        <tr class="dark:bg-gray-800 border-b dark:border-gray-700">
            <td class="p-2">
                <select name="items[__i__][product_id]"
                        class="product w-full rounded 
                               text-gray-800 dark:text-gray-200 dark:bg-gray-900 
                               border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="" class="dark:bg-gray-800">-- Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                class="dark:bg-gray-800">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="p-2 text-right price">0.00</td>
            <td class="p-2 text-center">
                <input type="number"
                       name="items[__i__][qty]"
                       class="qty w-20 text-center rounded 
                              text-gray-800 dark:text-gray-200 dark:bg-gray-900 
                              border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                       min="1" value="1">
            </td>
            <td class="p-2 text-right subtotal-display">0.00</td>
            <td class="p-2 text-center">
                <input type="hidden" class="price-input" name="items[__i__][price]">
                <input type="hidden" class="subtotal-input" name="items[__i__][subtotal]">
                <input type="hidden" class="tax-code-input" name="items[__i__][tax_code]">
                <input type="hidden" class="tax-rate-input" name="items[__i__][tax_rate]">
                <input type="hidden" class="tax-amount-input" name="items[__i__][tax_amount]">
                <input type="hidden" class="total-input" name="items[__i__][total]">
                <button type="button" class="remove-row text-red-600 hover:text-red-700">✕</button>
            </td>
        </tr>
    </template>
    
    {{-- SCRIPT --}}
    @push('scripts')
        <script>
            // Helper function to format numbers with comma (for display)
            function formatNumber(number) {
                return parseFloat(number).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            // Function untuk parsing Discount
            function parseDiscount(input, total) {
                if (!input) return { type: null, value: 0, amount: 0 };

                input = input.toString().replace(/\s/g, '');

                // Persentase
                if (input.includes('%')) {
                    const val = parseFloat(input.replace('%','')) || 0;
                    return {
                        type: 'percent',
                        value: val,
                        amount: total * (val / 100)
                    };
                }

                // Nominal
                const num = parseFloat(input.replace(/,/g,'')) || 0;
                return {
                    type: 'amount',
                    value: num,
                    amount: num
                };
            }

            let index = 0;

            function recalc() {
                let totalAmount = 0;
                let totalTax = 0;

                const ppnRate = parseFloat(document.getElementById('ppn_rate').value) || 0;
                const pphRate = parseFloat(document.getElementById('pph_rate').value) || 0;
                const discountInput = document.getElementById('discount').value;
                const discount = parseDiscount(discountInput, totalAmount);

                document.querySelectorAll('#items-table tbody tr').forEach(row => {
                    let priceText = row.querySelector('.price').innerText.replace(/,/g,'');
                    let price = parseFloat(priceText) || 0;
                    let qty = parseFloat(row.querySelector('.qty').value) || 0;

                    let subtotal = price * qty;
                    let taxRate = 0;
                    let taxAmount = 0;

                    // Logika PPN: Dikenakan pada Subtotal
                    if (ppnRate > 0) {
                        taxRate = ppnRate;
                        taxAmount = subtotal * (ppnRate / 100);
                    }
                    
                    // Logika PPh: Pemotongan, jadi bersifat mengurangi
                    if (pphRate > 0) {
                        // Jika ada PPN, PPh dihitung setelah PPN? (Biasanya PPh dari Dasar Pengenaan Pajak/DPP, yaitu Subtotal)
                        // Karena PPh adalah pemotongan, kita treat sebagai pajak negatif.
                        taxRate = pphRate;
                        taxAmount -= subtotal * (pphRate / 100); 
                    }

                    let total = subtotal + taxAmount; // Total per baris (termasuk pajak)

                    totalAmount += subtotal; // Total Subtotal dari semua baris
                    totalTax += taxAmount;   // Total PPN/PPh dari semua baris

                    // UI Update
                    row.querySelector('.subtotal-display').innerText = formatNumber(subtotal);

                    // Hidden Update
                    row.querySelector('.price-input').value = price;
                    row.querySelector('.subtotal-input').value = subtotal;
                    row.querySelector('.tax-rate-input').value = taxRate; // Ini mungkin perlu disesuaikan jika PPN dan PPh terpisah
                    row.querySelector('.tax-amount-input').value = taxAmount; 
                    row.querySelector('.total-input').value = total;
                });

                // Kalkulasi Akhir
                // const discount = parseDiscount(discountInput, totalAmount);
                const grandTotal = totalAmount + totalTax - discount.amount;

                // Update Display (KOLOM KANAN)
                document.getElementById('sub-total-display').innerText = formatNumber(totalAmount);
                document.getElementById('discount-amount-display').innerText = formatNumber(discount.amount * -1); // Tampilkan diskon sebagai nilai negatif
                document.getElementById('total-tax-display').innerText = formatNumber(totalTax);
                document.getElementById('grand-total-display').innerText = formatNumber(grandTotal);
                
                // Update Hidden Inputs
                document.getElementById('total-amount').value = totalAmount.toFixed(2);
                document.getElementById('total-tax').value = totalTax.toFixed(2);
                document.getElementById('grand-total-input').value = grandTotal.toFixed(2);
                
                // Hidden fields Discount
                document.getElementById('discount-type').value = discount.type || '';
                document.getElementById('discount-value').value = discount.value.toFixed(2);
                document.getElementById('discount-amount').value = discount.amount.toFixed(2);
            }

            // ... (Fungsi add-row, change event, input event, click event - tidak diubah, hanya memastikan penggunaan formatNumber)
            
            document.getElementById('add-row').onclick = () => {
                let tpl = document.getElementById('row-template').innerHTML
                    .replace(/__i__/g, index++);
                document.querySelector('#items-table tbody')
                    .insertAdjacentHTML('beforeend', tpl);
                
                // Pemicu recalc saat baris baru ditambahkan
                recalc(); 
            };

            document.addEventListener('change', e => {
                if (e.target.classList.contains('product')) {
                    let row = e.target.closest('tr');
                    let price = e.target.selectedOptions[0].dataset.price || 0;
                    
                    row.querySelector('.price').innerText = formatNumber(price);
                    
                    let qty = row.querySelector('.qty').value;
                    let subtotal = (parseFloat(price) * parseFloat(qty));
                    row.querySelector('.subtotal-display').innerText = formatNumber(subtotal);
                    
                    recalc();
                }
            });

            document.addEventListener('input', e => {
                if (e.target.classList.contains('qty')) {
                    let row = e.target.closest('tr');
                    let priceText = row.querySelector('.price').innerText.replace(/,/g,''); 
                    let price = parseFloat(priceText) || 0;
                    
                    let qty = e.target.value;
                    let subtotal = (price * parseFloat(qty));
                    row.querySelector('.subtotal-display').innerText = formatNumber(subtotal);
                    
                    recalc();
                }
            });

            document.addEventListener('click', e => {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    recalc();
                }
            });

            // Event Listeners untuk input Discount/Pajak
            ['discount','ppn_rate','pph_rate'].forEach(id => {
                document.getElementById(id).addEventListener('input', recalc);
            });

            // add initial row (Dipastikan ada minimal 1 baris saat load)
            document.getElementById('add-row').click();
        </script>
    @endpush
</x-app-layout>