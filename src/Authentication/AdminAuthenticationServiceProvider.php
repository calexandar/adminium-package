<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Authentication;

use Calexandar\Adminium\Authentication\SignIn\SignInAdminCommand;
use Calexandar\Adminium\Authentication\SignIn\SignInAdminCommandHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class AdminAuthenticationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        $this->registerCommandHandlers();
        $this->loadViewsFrom(__DIR__.'/Views', 'authentication');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerCommandHandlers(): void
    {
        Bus::map([
            SignInAdminCommand::class => SignInAdminCommandHandler::class,

        ]);
    }
}
