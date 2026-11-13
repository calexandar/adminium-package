<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests;

use Calexandar\Adminium\Tests\Fixtures\ReportsModuleServiceProvider;

abstract class ModuleTestCase extends TestCase
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            ReportsModuleServiceProvider::class,
        ];
    }
}
