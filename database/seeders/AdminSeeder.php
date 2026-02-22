<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create core permissions (Optional for future expansion)
        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-categories',
            'manage-products',
            'manage-settings',
            'view-customers',
            'manage-reports',
            'manage-orders',
            'view-orders'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create "Super Admin" role and assign all available permissions
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole->syncPermissions(Permission::all());

        // 3. Create the initial Super Admin account
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@store.com'], // Default email address
            [
                'name' => 'Main Manager',
                'password' => Hash::make('12345678'), // Default password
                'email_verified_at' => now(),
            ]
        );

        // 4. Assign the Super Admin role to the user
        $adminUser->assignRole($adminRole);
    }
}
