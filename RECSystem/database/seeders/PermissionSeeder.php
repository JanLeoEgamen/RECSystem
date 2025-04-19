<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permission groups
        $permissionGroups = [
            'articles' => ['view', 'edit', 'create', 'delete'],
            'communities' => ['view', 'edit', 'create', 'delete'],
            'event announcements' => ['view', 'edit', 'create', 'delete'],
            'faqs' => ['view', 'edit', 'create', 'delete'],
            'main carousels' => ['view', 'edit', 'create', 'delete'],
            'permissions' => ['view', 'edit', 'create', 'delete'],
            'roles' => ['view', 'edit', 'create', 'delete'],
            'supporters' => ['view', 'edit', 'create', 'delete'],
            'users' => ['view', 'edit', 'create', 'delete'],
            'sections' => ['view', 'edit', 'create', 'delete'],
            'bureaus' => ['view', 'edit', 'create', 'delete'],
            'membership types' => ['view', 'edit', 'create', 'delete'],
            'markees' => ['view', 'edit', 'create', 'delete'],
        ];

        // Create permissions
        foreach ($permissionGroups as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$resource}",
                    'guard_name' => 'web'
                ]);
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }

    
}
