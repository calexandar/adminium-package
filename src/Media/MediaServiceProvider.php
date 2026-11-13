<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Media;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class MediaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'media',
            label: 'Media',
            route: 'admin.media.index',
            permission: UserPermissionName::MANAGE_MEDIA->value,
            icon: 'media',
            order: 80,
            active: ['admin.media.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'media');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
