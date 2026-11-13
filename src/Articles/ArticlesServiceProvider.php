<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Articles;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class ArticlesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'articles',
            label: 'Articles',
            route: 'admin.articles.index',
            permission: UserPermissionName::MANAGE_ARTICLES->value,
            icon: 'articles',
            order: 60,
            active: ['admin.articles.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'articles');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
