<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            PartnerSeeder::class,
            CategorySeeder::class,
            TaxRuleSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);

        User::factory(5)->create();
    }
}
