<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface SmsHttpClient
{
    /**
     * Send HTTP POST request
     *
     * @param  string  $url  Request URL
     * @param  array  $payload  Request payload
     * @param  array  $headers  Request headers
     * @return array Response data
     */
    public function post(string $url, array $payload, array $headers): array;

    /**
     * Check if response indicates authentication failure
     *
     * @param  int  $statusCode  HTTP status code
     * @return bool True if authentication failed
     */
    public function isAuthenticationFailure(int $statusCode): bool;
}
