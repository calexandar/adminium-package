<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Products;

use Calexandar\Adminium\Facades\Adminium;
use Calexandar\Adminium\Navigation\NavigationItem;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Illuminate\Support\ServiceProvider;

final class ProductsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Adminium::navigation()->add(NavigationItem::make(
            id: 'products',
            label: 'Products',
            route: 'admin.products.index',
            permission: UserPermissionName::MANAGE_PRODUCTS->value,
            icon: 'products',
            order: 40,
            active: ['admin.products.*'],
        ));

        $this->loadViewsFrom(__DIR__.'/Views', 'products');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->register(RouteServiceProvider::class);
    }
}
