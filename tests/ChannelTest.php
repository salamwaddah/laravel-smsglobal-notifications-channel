<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use SalamWaddah\SmsGlobal\SmsGlobalChannel;
use SalamWaddah\SmsGlobal\SmsGlobalMessage;

class ChannelTest extends TestCase
{
    /**
     * @test
     */
    public function to_array_has_correct_content(): void
    {
        Config::set('services.sms_global.origin', 'Salam');

        $channel = new SmsGlobalChannel();
        $message = new SmsGlobalMessage();

        $message->to('+971555555555');
        $message->content('hi there');

        $expected = [
            'destination' => '+971555555555',
            'message' => 'hi there',
            'origin' => 'Salam',
        ];

        $this->assertSame(
            $expected, $channel->toArray($message)
        );
    }
}
