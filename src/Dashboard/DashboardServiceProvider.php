<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Dashboard;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;

final class DashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'dashboard',
            label: 'Dashboard',
            route: 'admin.dashboard',
            permission: null,
            icon: 'dashboard',
            order: 10,
            active: ['admin.dashboard*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'dashboard');

        $this->app->register(RouteServiceProvider::class);
    }
}
