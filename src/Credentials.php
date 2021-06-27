<?php

namespace SalamWaddah\SmsGlobal;

use Illuminate\Support\Facades\Config;

class Credentials
{
    public const HASH_ALGO = 'sha256';

    private static string $apiKey;
    private static string $secretKey;

    private string $smsPath = '/v2/sms';
    private string $baseUrl = 'https://api.smsglobal.com';

    public function __construct()
    {
        self::setApiKey(
            Config::get('services.sms_global.api_key')
        );

        self::setSecretKey(
            Config::get('services.sms_global.api_secret')
        );
    }

    public function getUrl(): string
    {
        return $this->baseUrl.$this->smsPath;
    }

    public function getAuthorizationHeader(): string
    {
        $timestamp = time();
        $nonce = md5(microtime().mt_rand());

        $hash = $this->hashRequest($timestamp, $nonce, $this->smsPath);
        $header = 'MAC id="%s", ts="%s", nonce="%s", mac="%s"';

        return sprintf($header, $this->getApiKey(), $timestamp, $nonce, $hash);
    }

    private function getApiKey(): string
    {
        return self::$apiKey;
    }

    private static function setApiKey(string $apiKey): void
    {
        self::$apiKey = $apiKey;
    }

    private static function setSecretKey(string $apiSecretKey): void
    {
        self::$secretKey = $apiSecretKey;
    }

    private function getSecretKey(): string
    {
        return self::$secretKey;
    }

    /**
     * Hashes a request using the API secret, for use in the Authorization
     * header.
     *
     * @param int    $timestamp  Unix timestamp of request time
     * @param string $nonce      Random unique string
     * @param string $requestUri Request URI (e.g. /v2/sms/)
     *
     * @return string
     */
    private function hashRequest(int $timestamp, string $nonce, string $requestUri): string
    {
        $port = 443;

        $host = 'api.smsglobal.com';
        $string = [$timestamp, $nonce, 'POST', $requestUri, $host, $port, ''];
        $string = sprintf("%s\n", implode("\n", $string));
        $hash = hash_hmac(self::HASH_ALGO, $string, $this->getSecretKey(), true);

        return base64_encode($hash);
    }
}