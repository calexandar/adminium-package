<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests;

abstract class DisabledModuleTestCase extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('adminium.modules.news', false);
        $app['config']->set('adminium.modules.settings', false);
    }
}
