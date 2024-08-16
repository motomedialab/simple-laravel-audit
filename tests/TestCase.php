<?php

namespace Motomedialab\SimpleLaravelAudit\Tests;

use Motomedialab\SimpleLaravelAudit\Providers\SimpleAuditServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SimpleAuditServiceProvider::class
        ];
    }
}
