<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\SignIn;

use Illuminate\Http\RedirectResponse;

final readonly class SignInAdminController
{
    public function __construct(private SignInAdminCommandHandler $signInAdminCommandHandler) {}

    public function __invoke(AdminSignInRequest $request): RedirectResponse
    {
        $this->signInAdminCommandHandler->handle(
            new SignInAdminCommand(
                $request->string('email')->value(),
                $request->string('password')->value(),
                $request->boolean('remember-me'),
            )
        );

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }
}
