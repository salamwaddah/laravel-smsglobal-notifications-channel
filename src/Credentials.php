<?php

namespace SalamWaddah\SmsGlobal;

use Illuminate\Support\Facades\Config;
use SalamWaddah\SmsGlobal\Exceptions\MissingConfiguration;

class Credentials
{
    public const HASH_ALGO = 'sha256';

    private static string $apiKey;

    private static string $secretKey;

    private string $smsPath = '/v2/sms';

    /**
     * @throws \SalamWaddah\SmsGlobal\Exceptions\MissingConfiguration
     */
    public function __construct()
    {
        $apiKey = Config::get('services.sms_global.api_key');
        $apiSecretKey = Config::get('services.sms_global.api_secret');

        if (! $apiKey || ! $apiSecretKey) {
            throw new MissingConfiguration('API key/secret is missing');
        }

        self::$apiKey = $apiKey;
        self::$secretKey = $apiSecretKey;
    }

    public function getUrl(): string
    {
        return 'https://api.smsglobal.com'.$this->smsPath;
    }

    public function getAuthorizationHeader(): string
    {
        $timestamp = time();
        $nonce = md5(microtime().mt_rand());

        $hash = $this->hashRequest($timestamp, $nonce, $this->smsPath);
        $header = 'MAC id="%s", ts="%s", nonce="%s", mac="%s"';

        return sprintf($header, self::$apiKey, $timestamp, $nonce, $hash);
    }

    /**
     * Hashes a request using the API secret, for use in the Authorization
     * header.
     *
     * @param  int  $timestamp  Unix timestamp of request time
     * @param  string  $nonce  Random unique string
     * @param  string  $requestUri  Request URI (e.g. /v2/sms/)
     */
    private function hashRequest(int $timestamp, string $nonce, string $requestUri): string
    {
        $port = 443;

        $host = 'api.smsglobal.com';
        $string = [$timestamp, $nonce, 'POST', $requestUri, $host, $port, ''];
        $string = sprintf("%s\n", implode("\n", $string));
        $hash = hash_hmac(self::HASH_ALGO, $string, self::$secretKey, true);

        return base64_encode($hash);
    }
}
