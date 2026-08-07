<?php

namespace Database\Seeders;

use App\Models\Company;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::firstOrCreate([
            'name' => 'PT Contoh Sejahtera',
        ], [
            'npwp' => '01.234.567.8-999.000',
            'is_pkp' => true,
            'address' => 'Jakarta'
        ]);
    }
}
