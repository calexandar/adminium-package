<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Database\Seeders;

use Illuminate\Database\Seeder;

final class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @deprecated Create administrators with "php artisan adminium:admin:create".
     */
    public function run(): void
    {
        if (isset($this->command)) {
            $this->command->warn('SuperAdminSeeder no longer creates a default account.');
            $this->command->line('Create an administrator with: php artisan adminium:admin:create');
        }
    }
}
