<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Transaction,
    TransactionItem,
    Company,
    Partner,
    Product,
    User
};
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $user    = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

        if (!$company || !$user) {
            $this->command->error('Company / Admin user not found');
            return;
        }

        $vendors   = Partner::where('type', 'vendor')->get();
        $customers = Partner::where('type', 'customer')->get();
        $freelancers = Partner::where('type', 'freelancer')->get();

        $productsBarang = Product::whereHas('category', fn ($q) => $q->where('is_service', false))->take(5)->get();
        $productsJasa   = Product::whereHas('category', fn ($q) => $q->where('is_service', true))->take(3)->get();

        /**
         * ==================================================
         * PURCHASE TRANSACTIONS (STOCK MASUK)
         * ==================================================
         */
        foreach ($vendors as $vendor) {

            $transactionDate = Carbon::now()->subDays(rand(10, 30));

            $transaction = Transaction::create([
                'company_id'       => $company->id,
                'partner_id'       => $vendor->id,
                'user_id'          => $user->id,
                'type'             => 'purchase',
                'transaction_date' => $transactionDate,
                'total_amount'     => 0,
                'total_tax'        => 0,
                'grand_total'      => 0,
            ]);

            $totalAmount = 0;

            foreach ($productsBarang->random(2) as $product) {
                $qty   = rand(5, 30);
                $price = $product->price * 0.85; // beli lebih murah

                $subtotal = $qty * $price;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $qty,
                    'price'          => $price,

                    'tax_code'   => 'NONE',
                    'tax_rate'   => 0,
                    'tax_amount' => 0,

                    'subtotal' => $subtotal,
                    'total'    => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            $transaction->update([
                'total_amount' => $totalAmount,
                'total_tax'    => 0,
                'grand_total'  => $totalAmount,
            ]);
        }

        /**
         * ==================================================
         * SALE TRANSACTIONS (STOCK KELUAR - BARANG)
         * ==================================================
         */
        foreach ($customers as $customer) {

            $transactionDate = Carbon::now()->subDays(rand(1, 15));

            $transaction = Transaction::create([
                'company_id'       => $company->id,
                'partner_id'       => $customer->id,
                'user_id'          => $user->id,
                'type'             => 'sale',
                'transaction_date' => $transactionDate,
                'total_amount'     => 0,
                'total_tax'        => 0,
                'grand_total'      => 0,
            ]);

            $totalAmount = 0;
            $totalTax    = 0;

            foreach ($productsBarang->random(2) as $product) {
                $qty   = rand(1, 10);
                $price = $product->price;

                $subtotal = $qty * $price;

                // PPN 11%
                $ppnRate   = 11;
                $ppnAmount = $subtotal * ($ppnRate / 100);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $qty,
                    'price'          => $price,

                    'tax_code'   => 'PPN',
                    'tax_rate'   => $ppnRate,
                    'tax_amount' => $ppnAmount,

                    'subtotal' => $subtotal,
                    'total'    => $subtotal + $ppnAmount,
                ]);

                $totalAmount += $subtotal;
                $totalTax    += $ppnAmount;
            }

            $transaction->update([
                'total_amount' => $totalAmount,
                'total_tax'    => $totalTax,
                'grand_total'  => $totalAmount + $totalTax,
            ]);
        }

        /**
         * ==================================================
         * SALE TRANSACTIONS - JASA (KENA PPh21)
         * ==================================================
         */
        foreach ($freelancers as $freelancer) {

            $product = $productsJasa->random();

            $price = $product->price;

            // PPh21 contoh 2.5%
            $pphRate   = 2.5;
            $pphAmount = $price * ($pphRate / 100);

            $transaction = Transaction::create([
                'company_id'       => $company->id,
                'partner_id'       => $freelancer->id,
                'user_id'          => $user->id,
                'type'             => 'sale',
                'transaction_date' => Carbon::now()->subDays(rand(1, 10)),

                'total_amount' => $price,
                'total_tax'    => $pphAmount,
                'grand_total'  => $price - $pphAmount,
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'qty'            => 1,
                'price'          => $price,

                'tax_code'   => 'PPh21',
                'tax_rate'   => $pphRate,
                'tax_amount' => $pphAmount,

                'subtotal' => $price,
                'total'    => $price - $pphAmount,
            ]);
        }
    }
}
