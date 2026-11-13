<?php

declare(strict_types=1);

namespace Calexandar\Adminium;

use Calexandar\Adminium\Articles\ArticlesServiceProvider;
use Calexandar\Adminium\Authentication\AdminAuthenticationServiceProvider;
use Calexandar\Adminium\Categories\CategoriesServiceProvider;
use Calexandar\Adminium\Console\AdminCreateCommand;
use Calexandar\Adminium\Console\InstallCommand;
use Calexandar\Adminium\Dashboard\DashboardServiceProvider;
use Calexandar\Adminium\Groups\GroupsServiceProvider;
use Calexandar\Adminium\Http\Middleware\Authenticate;
use Calexandar\Adminium\Media\MediaServiceProvider;
use Calexandar\Adminium\News\NewsServiceProvider;
use Calexandar\Adminium\Pages\PagesServiceProvider;
use Calexandar\Adminium\Products\ProductsServiceProvider;
use Calexandar\Adminium\Settings\SettingsServiceProvider;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Calexandar\Adminium\Users\UsersServiceProvider;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AdminiumServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, class-string<ServiceProvider>>
     */
    private const MODULES = [
        'authentication' => AdminAuthenticationServiceProvider::class,
        'dashboard' => DashboardServiceProvider::class,
        'users' => UsersServiceProvider::class,
        'categories' => CategoriesServiceProvider::class,
        'products' => ProductsServiceProvider::class,
        'articles' => ArticlesServiceProvider::class,
        'groups' => GroupsServiceProvider::class,
        'news' => NewsServiceProvider::class,
        'media' => MediaServiceProvider::class,
        'pages' => PagesServiceProvider::class,
        'settings' => SettingsServiceProvider::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/adminium.php', 'adminium');

        $this->configureAuth();

        foreach (self::MODULES as $module => $provider) {
            if ((bool) config('adminium.modules.'.$module, true)) {
                $this->app->register($provider);
            }
        }
    }

    public function boot(): void
    {
        $this->registerSuperAdminGate();

        $this->app->make(Router::class)->aliasMiddleware('adminium.auth', Authenticate::class);

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            AdminCreateCommand::class,
            InstallCommand::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/adminium.php' => config_path('adminium.php'),
        ], 'adminium-config');

        $this->publishes([
            __DIR__.'/../resources/css' => resource_path('css/vendor/adminium'),
            __DIR__.'/../resources/js' => resource_path('js/vendor/adminium'),
        ], 'adminium-assets');
    }

    private function configureAuth(): void
    {
        $guard = adminium_guard();
        $provider = adminium_auth_provider();
        $model = adminium_user_model();

        $config = $this->app->make(ConfigRepository::class);

        if (! $config->has('auth.guards.'.$guard)) {
            $config->set('auth.guards.'.$guard, [
                'driver' => 'session',
                'provider' => $provider,
            ]);
        }

        if (! $config->has('auth.providers.'.$provider)) {
            $config->set('auth.providers.'.$provider, [
                'driver' => 'eloquent',
                'model' => $model,
            ]);
        }
    }

    private function registerSuperAdminGate(): void
    {
        $role = config('adminium.super_admin_role');

        if (is_string($role) && $role !== '') {
            Gate::before(function (object $user) use ($role): ?bool {
                if (! method_exists($user, 'hasRole')) {
                    return null;
                }

                return $user->hasRole($role, adminium_permission_guard()) ? true : null;
            });
        }

        foreach (UserPermissionName::cases() as $permission) {
            Gate::define($permission->value, static function (object $user) use ($permission): bool {
                if (! method_exists($user, 'checkPermissionTo')) {
                    return false;
                }

                return $user->checkPermissionTo($permission->value, adminium_permission_guard());
            });
        }
    }
}
