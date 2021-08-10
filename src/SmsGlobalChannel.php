<?php

/** @noinspection ALL */

namespace SalamWaddah\SmsGlobal;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsGlobalChannel
{
    private Credentials $credentials;

    public function __construct()
    {
        $this->credentials = new Credentials();
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send($notifiable, Notification $notification): void
    {
        /* @var SmsGlobalMessage $message */
        $message = $notification->toSmsGlobal($notifiable);

        Log::info(
            sprintf(
                'SMS GLOBAL: Sending sms to %s: %s',
                $message->getTo(),
                $message->getContent()
            ),
            $this->toArray($message)
        );

        if (Config::get('services.sms_global.debug')) {
            Log::debug('SMS GLOBAL: Debug mode is ON.');

            return;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->credentials->getAuthorizationHeader(),
            'Content-Type' => 'application/json',
        ])->post($this->credentials->getUrl(), $this->toArray($message));

        $response->throw();
    }

    public function toArray(SmsGlobalMessage $message): array
    {
        return [
            'destination' => $message->getTo(),
            'message' => $message->getContent(),
            'origin' => $message->getOrigin(),
        ];
    }
}
