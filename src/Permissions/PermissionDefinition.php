<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Permissions;

use InvalidArgumentException;

final readonly class PermissionDefinition
{
    public function __construct(
        public string $name,
        public string $label,
        public string $module,
    ) {
        if ($name === '') {
            throw new InvalidArgumentException('A permission must have a stable name.');
        }

        if ($label === '') {
            throw new InvalidArgumentException("The permission [{$name}] must have a label.");
        }

        if ($module === '') {
            throw new InvalidArgumentException("The permission [{$name}] must belong to a module.");
        }
    }

    public static function make(string $name, string $label, string $module): self
    {
        return new self($name, $label, $module);
    }
}
