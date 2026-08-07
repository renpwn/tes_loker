<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        Partner::insert([
            // =====================
            // CUSTOMER
            // =====================
            [
                'name' => 'PT Customer Jaya',
                'type' => 'customer',
                'npwp' => '02.111.222.3-888.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Nusantara Abadi',
                'type' => 'customer',
                'npwp' => '01.234.567.8-901.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Sejahtera Bersama',
                'type' => 'customer',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Global Teknologi',
                'type' => 'customer',
                'npwp' => '09.876.543.2-111.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UD Makmur Sentosa',
                'type' => 'customer',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // VENDOR
            // =====================
            [
                'name' => 'CV Vendor Makmur',
                'type' => 'vendor',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Sumber Elektronik',
                'type' => 'vendor',
                'npwp' => '03.333.444.5-666.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Mitra Logistik',
                'type' => 'vendor',
                'npwp' => '07.222.111.9-555.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Sinar Teknik',
                'type' => 'vendor',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Indo Supplier',
                'type' => 'vendor',
                'npwp' => '05.444.333.1-777.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // FREELANCER / JASA ORANG PRIBADI
            // =====================
            [
                'name' => 'Budi Santoso',
                'type' => 'freelancer',
                'npwp' => '12.345.678.9-012.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andi Pratama',
                'type' => 'freelancer',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aisyah',
                'type' => 'freelancer',
                'npwp' => '98.765.432.1-999.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rizky Mahendra',
                'type' => 'freelancer',
                'npwp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'type' => 'freelancer',
                'npwp' => '11.222.333.4-555.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
