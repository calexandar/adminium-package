<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Categories;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class CategoriesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'categories',
            label: 'Categories',
            route: 'admin.categories.index',
            permission: UserPermissionName::MANAGE_CATEGORIES->value,
            icon: 'categories',
            order: 30,
            active: ['admin.categories.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'categories');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
