<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\SignIn;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final readonly class SignInAdminViewController
{
    public function __invoke(): View|RedirectResponse
    {
        if (Auth::guard(adminium_guard())->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('authentication::sign_in');
    }
}
