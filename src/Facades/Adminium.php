<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Facades;

use Calexandar\Adminium\AdminiumManager;
use Calexandar\Adminium\Navigation\NavigationRegistry;
use Calexandar\Adminium\Permissions\PermissionRegistry;
use Illuminate\Support\Facades\Facade;

/**
 * @method static NavigationRegistry navigation()
 * @method static PermissionRegistry permissions()
 *
 * @see AdminiumManager
 */
final class Adminium extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminiumManager::class;
    }
}
