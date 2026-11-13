<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Groups;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class GroupsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'groups',
            label: 'Groups',
            route: 'admin.groups.index',
            permission: UserPermissionName::MANAGE_GROUPS->value,
            icon: 'groups',
            order: 50,
            active: ['admin.groups.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'groups');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
