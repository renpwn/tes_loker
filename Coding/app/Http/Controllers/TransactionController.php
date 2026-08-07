<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    /**
     * LIST TRANSACTIONS
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        if ($request->ajax()) {
            $query = Transaction::with([
                'creator:id,name',
                'partner:id,name',
                'items:id,transaction_id,subtotal'
            ]);

            // 🔥 FILTER BERDASARKAN TYPE
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            return DataTables::of($query)
                ->addColumn('date', fn ($t) =>
                    $t->transaction_date->format('d-m-Y')
                )
                ->addColumn('code', fn ($t) =>
                    'TRX-' . str_pad($t->id, 6, '0', STR_PAD_LEFT)
                )
                ->addColumn('type', fn ($t) =>
                    ucfirst($t->type)
                )
                ->addColumn('total', fn ($t) =>
                    number_format($t->items->sum('subtotal'), 2)
                )
                // Menampilkan nama user dari relasi (meskipun kita menggunakan JOIN untuk sorting/filtering)
                ->addColumn('creator.name', fn ($t) => $t->creator_name)
                ->addColumn('actions', fn ($t) =>
                    view('transactions.actions', compact('t'))->render()
                )
                ->rawColumns(['actions'])

                // --- PENGATURAN SORTING/FILTERING ---            
                // 1. DATE (Mengurutkan berdasarkan kolom fisik 'transaction_date')
                ->orderColumn('date', function ($query, $order) {
                    $query->orderBy('transactions.transaction_date', $order);
                })

                // 2. CODE (Mengurutkan berdasarkan kolom fisik 'id')
                ->orderColumn('code', function ($query, $order) {
                    $query->orderBy('transactions.id', $order);
                })
                
                // 3. TOTAL (Mengurutkan berdasarkan hasil agregat)
                ->orderColumn('total', function ($query, $order) {
                    // total dari relation, perlu subquery
                    $query->withSum('items as total_amount', 'subtotal')
                        ->orderBy('total_amount', $order);
                })

                // 4. USER/Creator Name (Mengurutkan berdasarkan alias creator_name dari JOIN)
                ->orderColumn('creator', function ($query, $order) {
                    $query->join('users', 'transactions.user_id', '=', 'users.id')
                        ->orderBy('users.name', $order)
                        ->select('transactions.*'); // penting agar query tetap valid
                })
                ->make(true);
        }

        return view('transactions.index', [
            'type' => $request->type, // optional, untuk UI
        ]);
    }

    /**
     * CREATE FORM
     */
    public function create(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $currentType = $request->query('type');
    
        // Inisialisasi query Partner
        $partnerQuery = Partner::query();

        if ($currentType === 'sale') {
            // Jika SALE, ambil partner yang bertipe 'customer'
            $partnerQuery->where('type', 'customer');
        } elseif ($currentType === 'purchase') {
            // Jika PURCHASE, ambil partner yang bertipe 'vendor' ATAU 'freelance'
            $partnerQuery->whereIn('type', ['vendor', 'freelancer']);
        } 
        // Jika $currentType null atau tidak valid, query akan mengambil semua partner (jika tidak ada filter default)

        // Ambil data partner yang sudah difilter
        $partners = $partnerQuery->get();

        return view('transactions.create', [
            'products' => Product::with('category')->get(),
            'partners' => $partners,
            'types' => ['sale', 'purchase'],
        ]);
    }

    /**
     * STORE TRANSACTION (IMMUTABLE)
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $validated = $request->validate([
            'type' => 'required|in:sale,purchase',
            'partner_id' => 'required|exists:partners,id',
            'transaction_date' => 'required|date',
            
            // --- TAMBAHKAN VALIDASI DISKON & TOTAL ---
            'total_tax' => 'required|numeric|min:0', // Pastikan ini ada di request
            'total_amount' => 'required|numeric|min:0', // Ini adalah subtotal
            'grand_total' => 'required|numeric', // Total akhir
            'discount_type' => 'nullable|in:percent,amount', // Tambahkan validasi tipe diskon
            'discount_value' => 'numeric|min:0', // Tambahkan validasi nilai diskon (misal: 10 atau 50000)
            'discount_amount' => 'numeric|min:0', // Tambahkan validasi nilai diskon dalam Rupiah
            // ------------------------------------------

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',

            // Tambahkan validasi untuk hidden inputs item
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.tax_amount' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated, $request) {

            $transaction = Transaction::create([
                'company_id' => Auth::user()->company_id,
                'partner_id' => $validated['partner_id'],
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'transaction_date' => $validated['transaction_date'],

                // --- SIMPAN DATA DISKON & TOTAL KE TRANSAKSI ---
                'total_tax' => $validated['total_tax'],
                'total_amount' => $validated['total_amount'], // Subtotal semua item
                'discount_type' => $validated['discount_type'] ?? null,
                'discount_value' => $validated['discount_value'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'grand_total' => $validated['grand_total'],
                // ------------------------------------------------
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // VALIDASI STOCK UNTUK SALE
                if ($validated['type'] === 'sale') {
                    if ($product->currentStock() < $item['qty']) {
                        throw new \Exception("Stock {$product->name} tidak cukup");
                    }
                }

                $price = $product->price;
                $subtotal = $price * $item['qty'];

                $itemTaxAmount = $item['tax_amount'];
                $total = $item['total'];
                // $itemTaxAmount = 0;
                // $taxCode = null;
                // $taxRate = 0;

                // 🧮 HITUNG PAJAK BERDASARKAN KATEGORI
                /*foreach ($product->category->taxRules as $tax) {

                    // PPN → tambah
                    if ($tax->code === 'PPN') {
                        $taxAmount = $subtotal * ($tax->rate / 100);
                    }

                    // PPh → potong
                    if (in_array($tax->code, ['PPh21', 'PPh23'])) {
                        $taxAmount = -1 * ($subtotal * ($tax->rate / 100));
                    }

                    $itemTaxAmount += $taxAmount;
                    $taxCode = $tax->code;
                    $taxRate = $tax->rate;
                }*/

                $transaction->items()->create([
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'tax_code' => $request->items[$item['product_id']]['tax_code'] ?? null, 
                    'tax_rate' => $request->items[$item['product_id']]['tax_rate'] ?? 0,
                    'tax_amount' => $itemTaxAmount,
                    'total' => $subtotal, // pajak bisa ditambahkan di sini
                ]);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction created successfully');
    }

    /**
     * SHOW DETAIL
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load([
            'creator',
            'partner',
            'items.product.category',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * DELETE (SOFT / HARD — TERGANTUNG KEBUTUHAN)
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        // rekomendasi: SOFT DELETE
        $transaction->delete();

        return back()->with('success', 'Transaction deleted');
    }
}
