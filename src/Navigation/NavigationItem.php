<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Navigation;

use InvalidArgumentException;

final readonly class NavigationItem
{
    /**
     * @param  list<string>  $active
     * @param  array<string, mixed>  $parameters
     */
    public function __construct(
        public string $id,
        public string $label,
        public string $route,
        public ?string $permission = null,
        public ?string $icon = null,
        public string $group = 'main',
        public int $order = 100,
        public array $active = [],
        public array $parameters = [],
    ) {
        if ($id === '') {
            throw new InvalidArgumentException('A navigation item must have a stable identifier.');
        }

        if ($label === '') {
            throw new InvalidArgumentException("The navigation item [{$id}] must have a label.");
        }

        if ($route === '') {
            throw new InvalidArgumentException("The navigation item [{$id}] must have a route name.");
        }
    }

    /**
     * @param  list<string>  $active
     * @param  array<string, mixed>  $parameters
     */
    public static function make(
        string $id,
        string $label,
        string $route,
        ?string $permission = null,
        ?string $icon = null,
        string $group = 'main',
        int $order = 100,
        array $active = [],
        array $parameters = [],
    ): self {
        return new self($id, $label, $route, $permission, $icon, $group, $order, $active, $parameters);
    }

    /**
     * @return list<string>
     */
    public function activePatterns(): array
    {
        return $this->active === [] ? [$this->route] : $this->active;
    }

    public function isActive(): bool
    {
        foreach ($this->activePatterns() as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }

    public function url(): string
    {
        return route($this->route, $this->parameters);
    }
}
