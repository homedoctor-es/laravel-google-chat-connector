<?php

namespace GoogleChatConnector\Tests;

use GoogleChatConnector\GoogleChatServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    public function getPackageProviders($app)
    {
        return [
            GoogleChatServiceProvider::class,
        ];
    }
}
