<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use SalamWaddah\SmsGlobal\Credentials;
use SalamWaddah\SmsGlobal\Exceptions\MissingConfiguration;

class CredentialsTest extends TestCase
{
    /**
     * @test
     */
    public function not_setting_api_key_throws_exception(): void
    {
        Config::set('services.sms_global.api_key');
        Config::set('services.sms_global.api_secret');

        $this->expectExceptionObject(
            new MissingConfiguration()
        );

        $this->expectExceptionMessage('API key/secret is missing');

        new Credentials();
    }

    /**
     * @test
     */
    public function credentials_api_url_is_correct(): void
    {
        $credentials = new Credentials();

        $this->assertSame(
            'https://api.smsglobal.com/v2/sms',
            $credentials->getUrl()
        );
    }

    /**
     * @test
     */
    public function auth_header_contains_required_keys(): void
    {
        $credentials = new Credentials();

        $header = $credentials->getAuthorizationHeader();

        $this->assertStringContainsString(
            'MAC id="this-is-api-key", ',
            $header
        );

        $this->assertStringContainsString(
            ' ts="'.time().'", ',
            $header
        );

        $this->assertStringContainsString(
            'nonce=',
            $header
        );

        $this->assertStringContainsString(
            ', mac="',
            $header
        );
    }
}
