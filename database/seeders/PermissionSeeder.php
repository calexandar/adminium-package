<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Database\Seeders;

use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserPermissionName::cases() as $permission) {
            Permission::findOrCreate($permission->value, adminium_permission_guard());
        }
    }
}
