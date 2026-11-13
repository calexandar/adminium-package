<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests\Fixtures;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationGroup;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\Permissions\PermissionDefinition;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class ReportsModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'reports');

        Adminium::permissions()->register(new PermissionDefinition(
            'view_reports',
            'View reports',
            'reports',
        ));

        Adminium::navigation()->group(NavigationGroup::make('reports', 'Reports', 50));
        Adminium::navigation()->add(NavigationItem::make(
            id: 'reports',
            label: 'Reports',
            route: 'admin.reports-test',
            permission: 'view_reports',
            icon: 'reports',
            group: 'reports',
            order: 5,
            active: ['admin.reports-test'],
        ));

        Route::middleware(['web', 'adminium.auth', 'can:view_reports'])
            ->prefix(config('adminium.route_prefix', 'admin'))
            ->get('reports-test', static fn () => view('reports::index'))
            ->name('admin.reports-test');
    }
}
