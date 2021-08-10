<?php

namespace SalamWaddah\SmsGlobal;

use Illuminate\Support\Facades\Config;

class SmsGlobalMessage
{
    private string $to;
    private string $content;

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getTo(): string
    {
        return str_replace(' ', '', $this->to) ?? '';
    }

    public function getOrigin(): string
    {
        return Config::get('services.sms_global.origin');
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
