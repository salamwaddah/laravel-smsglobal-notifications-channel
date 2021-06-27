<?php

namespace Tests;

use Illuminate\Support\Facades\Config;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.sms_global.api_key', 'this-is-api-key');
        Config::set('services.sms_global.api_secret', 'this-is-secret-key');
    }
}
