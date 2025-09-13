<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Category permissions
            'create_category',
            'edit_category',
            'view_category',
            'delete_category',

            // Product permissions
            'create_product',
            'edit_product',
            'view_product',
            'delete_product',

            // dashboard permissions
            'view_dashboard',   

            // sales permissions
            'create_sale',
            'view_sale',
            'cancel_sale',


            // customer permissions
            'create_customer',
            'edit_customer',
            'view_customer',
            'delete_customer',


            
            // User permissions
            'create_user',
            'edit_user',
            'view_user',
            'delete_user',
            
            // Role permissions
            'create_role',
            'edit_role',
            'view_role',
            'delete_role',
            
            // Permission permissions
            'view_permission',
            'assign_permission',

            // Pos system
            'create_pos',
            'view_pos',
            'delete_pos',


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create a default admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
            ]
        );

        
        $adminUser->assignRole('admin');
    }
}
