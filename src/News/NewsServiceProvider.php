<?php

declare(strict_types=1);

namespace Calexandar\Adminium\News;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class NewsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'news',
            label: 'News',
            route: 'admin.news.index',
            permission: UserPermissionName::MANAGE_NEWS->value,
            icon: 'news',
            order: 70,
            active: ['admin.news.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'news');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
