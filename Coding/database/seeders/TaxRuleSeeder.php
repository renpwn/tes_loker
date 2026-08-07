<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\TaxRule;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ppn = TaxRule::firstOrCreate([
            'code' => 'PPN'
        ], [
            'name' => 'Pajak Pertambahan Nilai',
            'rate' => 11.00
        ]);

        $pph21 = TaxRule::firstOrCreate([
            'code' => 'PPH21'
        ], [
            'name' => 'Pajak Penghasilan 21',
            'rate' => 2.50
        ]);

        $pph23 = TaxRule::firstOrCreate([
            'code' => 'PPH23'
        ], [
            'name' => 'Pajak Penghasilan Pasal 23',
            'rate' => 2.00
        ]);

        $barang = Category::where('code', 'BRG')->first();
        $jasa   = Category::where('code', 'JASA')->first();

        if ($barang) {
            $barang->taxRules()->syncWithoutDetaching([$ppn->id]);
        }

        if ($jasa) {
            $jasa->taxRules()->syncWithoutDetaching([
                $ppn->id,
                $pph21->id,
                $pph23->id
            ]);
        }
    }
}
