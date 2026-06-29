<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
Use App\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $staff = Role::where('name', 'staff')->first();

        $admin->permissions()->sync(Permission::all()->pluck('id'));
        $staff->permissions()->sync(
            Permission::where('name', 'user.view')->pluck('id')
        );

    }
}
