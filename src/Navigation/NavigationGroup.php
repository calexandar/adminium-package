<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Navigation;

use InvalidArgumentException;

final readonly class NavigationGroup
{
    public function __construct(
        public string $id,
        public string $label,
        public int $order = 100,
    ) {
        if ($id === '') {
            throw new InvalidArgumentException('A navigation group must have a stable identifier.');
        }

        if ($label === '') {
            throw new InvalidArgumentException("The navigation group [{$id}] must have a label.");
        }
    }

    public static function make(string $id, string $label, int $order = 100): self
    {
        return new self($id, $label, $order);
    }
}
