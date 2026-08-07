<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();

        User::firstOrCreate(
            ['email' => 'owner@company.test'],
            [
                'name' => 'Owner',
                'password' => Hash::make('12345'),
                'company_id' => $company->id,
                'role_id' => $ownerRole->id
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@company.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345'),
                'company_id' => $company->id,
                'role_id' => $adminRole->id
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@company.test'],
            [
                'name' => 'Staff',
                'password' => Hash::make('12345'),
                'company_id' => $company->id,
                'role_id' => $staffRole->id
            ]
        );
    }
}
