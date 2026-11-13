<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Permissions;

use Illuminate\Support\Str;

final class PermissionRegistry
{
    /**
     * @var array<string, PermissionDefinition>
     */
    private array $permissions = [];

    public function register(PermissionDefinition|string $permission, ?string $label = null, ?string $module = null): self
    {
        if (is_string($permission)) {
            $permission = PermissionDefinition::make(
                $permission,
                $label ?? Str::headline($permission),
                $module ?? 'application',
            );
        }

        $this->permissions[$permission->name] = $permission;

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->permissions[$name]);
    }

    public function get(string $name): ?PermissionDefinition
    {
        return $this->permissions[$name] ?? null;
    }

    /**
     * @return list<PermissionDefinition>
     */
    public function all(): array
    {
        return array_values($this->permissions);
    }

    /**
     * @return list<string>
     */
    public function names(): array
    {
        return array_keys($this->permissions);
    }
}
