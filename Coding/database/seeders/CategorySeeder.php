<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // BARANG
            ['code' => 'BRG-UMUM', 'name' => 'Barang Umum', 'is_service' => false],
            ['code' => 'BRG-ATK',  'name' => 'Alat Tulis Kantor', 'is_service' => false],
            ['code' => 'BRG-ELK',  'name' => 'Elektronik', 'is_service' => false],
            ['code' => 'BRG-IT',   'name' => 'Perangkat IT', 'is_service' => false],
            ['code' => 'BRG-MES',  'name' => 'Mesin & Sparepart', 'is_service' => false],

            // JASA
            ['code' => 'JASA-KON', 'name' => 'Jasa Konsultan', 'is_service' => true],
            ['code' => 'JASA-IT',  'name' => 'Jasa IT & Software', 'is_service' => true],
            ['code' => 'JASA-PRJ', 'name' => 'Jasa Proyek', 'is_service' => true],
            ['code' => 'JASA-OPS', 'name' => 'Jasa Operasional', 'is_service' => true],
            ['code' => 'JASA-FRL', 'name' => 'Jasa Freelancer', 'is_service' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['code' => $category['code']],
                [
                    'name' => $category['name'],
                    'is_service' => $category['is_service'],
                ]
            );
        }
    }
}
