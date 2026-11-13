<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Users;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class UsersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'users',
            label: 'Users',
            route: 'admin.users.index',
            permission: UserPermissionName::MANAGE_USERS->value,
            icon: 'users',
            order: 20,
            active: ['admin.users.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'users');

        $this->app->register(RouteServiceProvider::class);
    }
}
