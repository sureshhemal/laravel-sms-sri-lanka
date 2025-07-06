<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface SmsServiceContract
{
    /**
     * Send an SMS message
     *
     * @param  string  $to  The recipient phone number(s). Can be a single number or comma-separated list.
     *                      Examples: '94701234567' or '94701234567,94712345678,94723456789'
     * @param  string  $message  The SMS message content
     * @param  array  $options  Additional options for the SMS
     * @return array The response from the SMS API
     */
    public function sendSms(string $to, string $message, array $options = []): array;
}
