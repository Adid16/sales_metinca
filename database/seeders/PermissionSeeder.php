<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            ['name' => 'user.view', 'label' => 'View User'],
            ['name' => 'user.create', 'label' => 'Create User'],
            ['name' => 'user.edit', 'label' => 'Edit User'],
            ['name' => 'user.delete', 'label' => 'Delete User'],
        ]);

    }
}
