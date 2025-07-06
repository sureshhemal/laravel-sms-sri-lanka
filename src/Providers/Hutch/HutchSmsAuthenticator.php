<?php

namespace Sureshhemal\SmsSriLanka\Providers\Hutch;

use Illuminate\Support\Facades\Http;
use Sureshhemal\SmsSriLanka\Contracts\SmsAuthenticator;

class HutchSmsAuthenticator implements SmsAuthenticator
{
    private ?string $accessToken = null;

    private ?string $refreshToken = null;

    private string $baseUrl = 'https://bsms.hutch.lk/api';

    public function __construct(
        private string $username,
        private string $password,
    ) {}

    /**
     * Authenticate with the SMS API to get access and refresh tokens
     *
     * @return array The authentication response containing tokens
     */
    public function authenticate(): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'X-API-VERSION' => 'v1',
        ])->post("{$this->baseUrl}/login", [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $data = $response->json();

        $this->accessToken = $data['accessToken'] ?? null;
        $this->refreshToken = $data['refreshToken'] ?? null;

        return $data;
    }

    /**
     * Get the current access token
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Get the current refresh token
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * Refresh the access token using the refresh token
     *
     * @return array The refresh response
     */
    public function refreshToken(): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'X-API-VERSION' => 'v1',
        ])->post("{$this->baseUrl}/refresh", [
            'refreshToken' => $this->refreshToken,
        ]);

        $data = $response->json();

        $this->accessToken = $data['accessToken'] ?? null;
        $this->refreshToken = $data['refreshToken'] ?? null;

        return $data;
    }
}
