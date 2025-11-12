<?php

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Config;
use SalamWaddah\SmsGlobal\SmsGlobalMessage;

class MessageTest extends TestCase
{
    #[Test]
    public function to_can_be_set(): void
    {
        $message = new SmsGlobalMessage();

        $message->to('1234');

        $this->assertSame(
            '1234',
            $message->getTo()
        );
    }

    #[Test]
    public function setting_to_removes_spaces(): void
    {
        $message = new SmsGlobalMessage();

        $message->to('1 2 34');

        $this->assertSame(
            '1234',
            $message->getTo()
        );
    }

    #[Test]
    public function getting_origin_matches_config(): void
    {
        Config::set('services.sms_global.origin', 'Salam');

        $message = new SmsGlobalMessage();

        $this->assertSame(
            'Salam',
            $message->getOrigin()
        );
    }

    #[Test]
    public function content_can_be_set(): void
    {
        $message = new SmsGlobalMessage();

        $message->content('hi this is a message');

        $this->assertSame(
            'hi this is a message',
            $message->getContent()
        );
    }
}
