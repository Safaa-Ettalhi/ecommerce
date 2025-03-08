<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view orders',
            'update orders',
            'delete orders',
            'view users',
            'edit users',
            'delete users',
            'view reviews',
            'approve reviews',
            'delete reviews',
            'access dashboard',
        ];

        foreach ($permissions as $permission) {
            // Vérifier si la permission existe déjà avant de la créer
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view products',
            'view categories',
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');
    }
}
