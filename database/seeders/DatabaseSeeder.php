<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contract;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\ContractRequirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    $this->call(ArticleSeeder::class);
    $this->call(SystemSettingSeeder::class);
        // ==========================================
        // 1. DATA USERS ASLI (TETAP SESUAI SETTINGAN LO)
        // ==========================================
        
        // 1. Staff Sales 1
        User::create([
            'name'     => 'Staff Sales 1',
            'email'    => 'ssales1@example.com',
            'password' => Hash::make('ssales123'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        // 2. Staff Sales 2
        User::create([
            'name'     => 'Staff Sales 2',
            'email'    => 'ssales2@example.com',
            'password' => Hash::make('ssales2abc'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        // 3. Manager Sales
        User::create([
            'name'     => 'Manager Sales',
            'email'    => 'msales@example.com',
            'password' => Hash::make('msales123'),
            'role'     => 'manager',
            'divisi'   => 'sales',
        ]);

        // 4. Manager Quality
        User::create([
            'name'     => 'Manager Quality',
            'email'    => 'mquality@example.com',
            'password' => Hash::make('mquality123'),
            'role'     => 'manager',
            'divisi'   => 'quality',
        ]);

        // 5. Manager PPC
        User::create([
            'name'     => 'Manager PPC',
            'email'    => 'mppc@example.com',
            'password' => Hash::make('mppc123'),
            'role'     => 'manager',
            'divisi'   => 'ppc',
        ]);

        // 6. Manager Development Engineering
        User::create([
            'name'     => 'Manager DE',
            'email'    => 'mde@example.com',
            'password' => Hash::make('mde123'),
            'role'     => 'manager',
            'divisi'   => 'design engineering',
        ]);

        // 7. Admin
        User::create([
            'name'     => 'Admin Metinca',
            'email'    => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'divisi'   => null,
        ]);

        // 8. Contoh Customer (Sabila Customer)
        $customer = User::create([
            'name'     => 'Sabila Customer',
            'email'    => 'sabila@example.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'divisi'   => null,
        ]);
        
         $customer = User::create([
            'name'     => 'Adi Dwi Nugroho',
            'email'    => 'adidwinugroho168@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'divisi'   => null,
        ]);


    }
}