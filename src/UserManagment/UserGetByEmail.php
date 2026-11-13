<?php

declare(strict_types=1);

namespace Calexandar\Adminium\UserManagment;

use Illuminate\Contracts\Auth\Authenticatable;

final class UserGetByEmail
{
    public function __invoke(string $email): Authenticatable
    {
        $model = adminium_user_model();

        return $model::query()->where('email', $email)->firstOrFail();
    }
}
