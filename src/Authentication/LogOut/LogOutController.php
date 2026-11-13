<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication\LogOut;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LogOutController
{
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::guard(adminium_guard())->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.sign-in.view');
    }
}
