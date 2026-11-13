<?php

declare(strict_types=1);

namespace Calexandar\Adminium;

use Calexandar\Adminium\Navigation\NavigationRegistry;
use Calexandar\Adminium\Permissions\PermissionRegistry;

final readonly class AdminiumManager
{
    public function __construct(
        private NavigationRegistry $navigation,
        private PermissionRegistry $permissions,
    ) {}

    public function navigation(): NavigationRegistry
    {
        return $this->navigation;
    }

    public function permissions(): PermissionRegistry
    {
        return $this->permissions;
    }
}
