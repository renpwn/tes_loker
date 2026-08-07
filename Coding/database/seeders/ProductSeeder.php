<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

        if (!$admin) {
            $this->command->error('Admin user not found');
            return;
        }

        $categories = Category::pluck('id', 'code');

        $products = [
            // ATK
            ['ATK-001', 'Pulpen Standard Hitam', 'BRG-ATK', 3500, 500],
            ['ATK-002', 'Pulpen Standard Biru', 'BRG-ATK', 3500, 500],
            ['ATK-003', 'Pensil 2B', 'BRG-ATK', 3000, 300],
            ['ATK-004', 'Buku Tulis A5', 'BRG-ATK', 6500, 200],
            ['ATK-005', 'Spidol Whiteboard', 'BRG-ATK', 12000, 150],

            // ELEKTRONIK
            ['ELK-001', 'Mouse Wireless Logitech', 'BRG-ELK', 175000, 50],
            ['ELK-002', 'Keyboard Mechanical', 'BRG-ELK', 550000, 40],
            ['ELK-003', 'Monitor 24 Inch', 'BRG-ELK', 2100000, 20],
            ['ELK-004', 'Flashdisk 64GB', 'BRG-ELK', 85000, 100],
            ['ELK-005', 'External HDD 1TB', 'BRG-ELK', 950000, 30],
            ['ELK-007', 'Scanner Dokumen', 'BRG-ELK', 1900000, 8],
            ['ELK-008', 'UPS 1200VA', 'BRG-ELK', 1750000, 10],

            // IT
            ['IT-001', 'Laptop Office i5', 'BRG-IT', 9200000, 15],
            ['IT-002', 'Laptop Developer i7', 'BRG-IT', 14500000, 10],
            ['IT-003', 'Router Mikrotik', 'BRG-IT', 750000, 25],
            ['IT-004', 'Switch 8 Port', 'BRG-IT', 350000, 40],
            ['IT-005', 'SSD NVMe 512GB', 'BRG-IT', 950000, 50],
            ['IT-006', 'RAM DDR4 16GB', 'BRG-IT', 850000, 60],
            ['IT-007', 'RAM DDR4 32GB', 'BRG-IT', 1600000, 40],
            ['IT-008', 'SSD SATA 1TB', 'BRG-IT', 1250000, 30],
            ['IT-009', 'Webcam Full HD', 'BRG-IT', 550000, 25],

            // MESIN
            ['MES-001', 'Mesin Bor', 'BRG-MES', 1200000, 10],
            ['MES-002', 'Gerinda Tangan', 'BRG-MES', 650000, 15],
            ['MES-003', 'Kompresor Mini', 'BRG-MES', 2200000, 5],
            ['MES-004', 'Pompa Air', 'BRG-MES', 1800000, 8],
            ['MES-005', 'Mesin Las', 'BRG-MES', 3500000, 6],
            ['MES-006', 'Mesin Potong Besi', 'BRG-MES', 4200000, 4],
            ['MES-007', 'Mesin Amplas', 'BRG-MES', 950000, 10],
            ['MES-008', 'Mesin Press Hidrolik', 'BRG-MES', 7800000, 2],

            // JASA IT
            ['JS-IT-001', 'Pembuatan Website Company Profile', 'JASA-IT', 4500000, 0],
            ['JS-IT-002', 'Pembuatan Web App', 'JASA-IT', 15000000, 0],
            ['JS-IT-003', 'Maintenance Server Bulanan', 'JASA-IT', 2500000, 0],
            ['JS-IT-004', 'Integrasi API', 'JASA-IT', 3500000, 0],
            ['JS-IT-005', 'Audit Keamanan Sistem', 'JASA-IT', 5000000, 0],
            ['JS-IT-006', 'Deploy Aplikasi ke Cloud', 'JASA-IT', 4000000, 0],
            ['JS-IT-007', 'Setup CI/CD Pipeline', 'JASA-IT', 5500000, 0],

            // JASA KONSULTAN
            ['JS-KON-001', 'Konsultasi IT Harian', 'JASA-KON', 750000, 0],
            ['JS-KON-002', 'Konsultasi Sistem ERP', 'JASA-KON', 12000000, 0],
            ['JS-KON-003', 'Pendampingan Implementasi', 'JASA-KON', 8000000, 0],
            ['JS-KON-004', 'Analisa Kebutuhan Sistem', 'JASA-KON', 6000000, 0],
            ['JS-KON-005', 'Review Arsitektur Sistem', 'JASA-KON', 7000000, 0],
            ['JS-KON-006', 'Training Internal IT', 'JASA-KON', 9000000, 0],
            ['JS-KON-007', 'Penyusunan SOP IT', 'JASA-KON', 6500000, 0],

            // FREELANCER
            ['JS-FRL-001', 'Freelance Backend Dev', 'JASA-FRL', 6000000, 0],
            ['JS-FRL-002', 'Freelance Frontend Dev', 'JASA-FRL', 5500000, 0],
            ['JS-FRL-003', 'Freelance UI/UX Designer', 'JASA-FRL', 5000000, 0],
            ['JS-FRL-004', 'Freelance QA Tester', 'JASA-FRL', 4500000, 0],
            ['JS-FRL-005', 'Freelance DevOps', 'JASA-FRL', 7000000, 0],            
            ['JS-FRL-006', 'Freelance Mobile Developer', 'JASA-FRL', 7500000, 0],
        ];

        foreach ($products as [$sku, $name, $categoryCode, $price, $stock]) {
            Product::firstOrCreate(
                ['sku' => $sku],
                [
                    'category_id'   => $categories[$categoryCode] ?? null,
                    'user_id'       => $admin->id,
                    'name'          => $name,
                    'price'         => $price,
                    'initial_stock' => $stock,
                ]
            );
        }
    }
}
