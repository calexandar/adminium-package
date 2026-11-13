<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\SignIn;

final readonly class AdminSignedIn
{
    public function __construct(
        public int|string $userId,
    ) {}
}
