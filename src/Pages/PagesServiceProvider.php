<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Pages;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class PagesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'pages',
            label: 'Pages',
            route: 'admin.pages.index',
            permission: UserPermissionName::MANAGE_PAGES->value,
            icon: 'pages',
            order: 90,
            active: ['admin.pages.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'pages');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
