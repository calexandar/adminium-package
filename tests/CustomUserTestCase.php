<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests;

use Calexandar\Adminium\Tests\Fixtures\CustomAdminUser;

abstract class CustomUserTestCase extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('adminium.user_model', CustomAdminUser::class);
    }
}
