<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Database\Seeders;

use Calexandar\Adminium\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = app(PermissionRegistry::class);

        foreach ($permissions->all() as $permission) {
            Permission::findOrCreate($permission->name, adminium_permission_guard());
        }
    }
}
