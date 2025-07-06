<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface SmsAuthenticatorContract
{
    /**
     * Authenticate with an API and get access tokens
     *
     * @return array Authentication response data
     */
    public function authenticate(): array;

    /**
     * Get the current access token
     *
     * @return string|null The access token
     */
    public function getAccessToken(): ?string;

    /**
     * Refresh an expired token
     *
     * @return array Refresh response data
     */
    public function refreshToken(): array;
}
