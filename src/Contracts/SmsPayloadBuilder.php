<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface SmsPayloadBuilder
{
    /**
     * Build SMS payload from options
     *
     * @param  string  $to  Recipient phone number(s)
     * @param  string  $message  SMS message content
     * @param  array  $options  SMS options
     * @param  array  $config  Provider configuration
     * @return array The prepared payload
     */
    public function build(string $to, string $message, array $options, array $config = []): array;
}
