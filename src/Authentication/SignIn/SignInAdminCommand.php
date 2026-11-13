<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\SignIn;

final readonly class SignInAdminCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
