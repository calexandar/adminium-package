<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Database\Seeders;

use Calexandar\Adminium\UserManagment\UserRoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRoleName::cases() as $role) {
            Role::findOrCreate($role->value, adminium_permission_guard());
        }
    }
}
