<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\SignIn;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Validation\ValidationException;
use RuntimeException;

final readonly class SignInAdminCommandHandler
{
    public function __construct(
        private AuthManager $auth,
        private Dispatcher $eventDispatcher,
    ) {}

    public function handle(SignInAdminCommand $signInAdminCommand): int|string
    {
        $guardName = adminium_guard();
        $guard = $this->auth->guard($guardName);

        if (! $guard instanceof StatefulGuard) {
            throw new RuntimeException("The [{$guardName}] guard is not stateful.");
        }

        if (! $guard->attempt([
            'email' => $signInAdminCommand->email,
            'password' => $signInAdminCommand->password,
        ], $signInAdminCommand->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = $guard->user();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $this->auth->shouldUse($guardName);

        $userId = $user->getAuthIdentifier();

        if (! is_int($userId) && ! is_string($userId)) {
            $userId = (string) $userId;
        }

        $this->eventDispatcher->dispatch(
            new AdminSignedIn($userId)
        );

        return $userId;
    }
}
