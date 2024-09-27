<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Define permissions
            $permissions = [
                // User management
                'view-users',
                'create-users',
                'edit-users',
                'delete-users',

                // Role management
                'view-roles',
                'create-role',
                'edit-role',
                'delete-role',
                'show-role',


                // Permission management
                'view-permissions',
                'create-permission',
                'edit-permission',
                'delete-permission',
                'show-permission',

                // Assigning/removing permissions to/from roles
                'assign-permissions',
                'remove-permissions',

                // Auth management
                ' register',
                'login',
                'logout',
                'refresh',
                'me',

                // Category management
                'get-SoftDeleted-Category',
                'get-categories',
                'store-category',
                'update-category',
                'show-category',
                'destroy-category',
                'restore-category',
                'forceDestroy-category',

                // Book management
                'get-SoftDeleted-Book',
                'get-books',
                'store-book',
                'update-book',
                'show-book',
                'destroy-book',
                'view-books with categories',
                'restore-book',
                'forceDestroy-book'
            ];

            // Create permissions in the database
            foreach ($permissions as $perm) {
                Permissions::firstOrCreate(
                    ['name' => $perm],
                    ['description' => ucfirst(str_replace('-', ' ', $perm))]
                );
            }

            // Create the Admin role and assign all permissions to it
            $adminRole = Roles::firstOrCreate(
                ['name' => 'مدير'], // "Manager" in Arabic
                ['description' => 'The highest administrative role']
            );
            $adminRole->permissions()->sync(Permissions::all()->pluck('id'));

            // Create the User role and assign specific permissions
            $userRole = Roles::firstOrCreate(
                ['name' => 'مستخدم'], // "User" in Arabic
                ['description' => 'The regular user role']
            );
            $userPermissions = [
                ' register',
                'view-books with categories',
                'login',
                'logout',
                'refresh',
                'me',
                'get-categories',
                'get-books'
            ];
            $userRole->permissions()->sync(
                Permissions::whereIn('name', $userPermissions)->pluck('id')
            );
            $managerRole = Roles::firstOrCreate(
                ['name' => 'مدير'], // "User" in Arabic
                ['description' => 'The manager user role']
            );
            $managerPermissions = [
                ' register',
                'view-books with categories',
                'login',
                'logout',
                'refresh',
                'me',
                'get-categories',
                'get-books'
            ];
            $managerRole->permissions()->sync(
                Permissions::whereIn('name', $managerPermissions)->pluck('id')
            );

            // Create an Admin user and assign the Admin role
            $adminUser = User::firstOrCreate(
                ['email' => 'safaa@gmail.com'],
                [
                    'name' => 'System Administrator',
                    'password' => bcrypt('12345678'), // Ensure to change the password
                ]
            );
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);

            // Create a regular user and assign the User role
            $normalUser = User::firstOrCreate(
                ['email' => 'user@gmail.com'],
                [
                    'name' => 'Regular User',
                    'password' => bcrypt('12345678'), // Ensure to change the password
                ]
            );
            $normalUser->roles()->syncWithoutDetaching([$userRole->id]);
            // Create a regular manager and assign the manager role
            $manager = User::firstOrCreate(
                ['email' => 'manager@gmail.com'],
                [
                    'name' => 'Regular manager',
                    'password' => bcrypt('12345678'), // Ensure to change the password
                ]
            );
            $manager->roles()->syncWithoutDetaching([$managerRole->id]);

            // Log a message indicating that roles and permissions were successfully set
            $this->command->info('Roles and permissions have been successfully assigned and linked to users.');

    }

}
