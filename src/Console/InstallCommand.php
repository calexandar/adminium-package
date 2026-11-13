<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Console;

use Calexandar\Adminium\Database\Seeders\PermissionSeeder;
use Calexandar\Adminium\Database\Seeders\RoleSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

final class InstallCommand extends Command
{
    protected $signature = 'adminium:install
        {--force : Overwrite existing published files}
        {--migrate : Run the database migrations after publishing}
        {--seed : Seed the roles and permissions}';

    protected $description = 'Install the Adminium admin panel';

    public function handle(): int
    {
        $this->publishConfig();
        $this->publishAssets();
        $this->publishDependencyMigrations();
        $this->linkStorage();

        if ((bool) $this->option('migrate')) {
            $this->call('migrate', ['--force' => true]);
        }

        if ((bool) $this->option('seed')) {
            $this->seed();
        }

        $this->newLine();
        $this->info('Adminium was installed successfully.');
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Run: php artisan migrate');
        $this->line('  2. Run: php artisan adminium:admin:create');
        $this->line('  3. Add the Adminium entries to vite.config.js:');
        $this->line("       'vendor/calexandar/adminium/resources/css/adminium.css',");
        $this->line("       'vendor/calexandar/adminium/resources/js/adminium.js',");
        $this->line('  4. Run: npm install @tailwindplus/elements');
        $this->line('  5. Run: npm run build');
        $this->line('  6. Sign in at /'.config('adminium.route_prefix', 'admin').'/sign-in');

        return self::SUCCESS;
    }

    private function publishConfig(): void
    {
        if (! (bool) $this->option('force') && file_exists(config_path('adminium.php'))) {
            $this->line('Adminium config already exists; skipping.');

            return;
        }

        $this->line('Publishing Adminium config...');

        $this->call('vendor:publish', [
            '--tag' => 'adminium-config',
            '--force' => (bool) $this->option('force'),
        ]);
    }

    private function publishAssets(): void
    {
        $cssPath = resource_path('css/vendor/adminium');
        $jsPath = resource_path('js/vendor/adminium');

        if (! (bool) $this->option('force') && file_exists($cssPath) && file_exists($jsPath)) {
            $this->line('Adminium assets already exist; skipping.');

            return;
        }

        $this->line('Publishing Adminium assets...');

        $this->call('vendor:publish', [
            '--tag' => 'adminium-assets',
            '--force' => (bool) $this->option('force'),
        ]);
    }

    private function publishDependencyMigrations(): void
    {
        if (! $this->hasMigration('create_permission_tables')) {
            $this->call('vendor:publish', [
                '--provider' => PermissionServiceProvider::class,
                '--tag' => 'permission-migrations',
            ]);
        }

        if (! $this->hasMigration('create_media_table')) {
            $this->call('vendor:publish', [
                '--provider' => MediaLibraryServiceProvider::class,
                '--tag' => 'medialibrary-migrations',
            ]);
        }
    }

    private function linkStorage(): void
    {
        if (file_exists(public_path('storage'))) {
            $this->line('Storage link already exists.');

            return;
        }

        $result = $this->call('storage:link');

        if ($result !== self::SUCCESS) {
            $this->error('The storage link could not be created. Check the storage and public directory permissions.');
        }
    }

    private function seed(): void
    {
        $roleTable = config('permission.table_names.roles', 'roles');
        $permissionTable = config('permission.table_names.permissions', 'permissions');

        if (! is_string($roleTable) || ! is_string($permissionTable) || ! Schema::hasTable($roleTable) || ! Schema::hasTable($permissionTable)) {
            $this->warn('Skipping seeding because the roles and permissions tables do not exist yet. Run: php artisan migrate');

            return;
        }

        $this->line('Seeding roles and permissions...');

        $this->call('db:seed', ['--class' => RoleSeeder::class, '--force' => true]);
        $this->call('db:seed', ['--class' => PermissionSeeder::class, '--force' => true]);
    }

    private function hasMigration(string $name): bool
    {
        return glob(database_path('migrations/*_'.$name.'.php')) !== [];
    }
}
