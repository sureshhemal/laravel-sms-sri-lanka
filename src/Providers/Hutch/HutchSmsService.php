<?php

namespace Sureshhemal\SmsSriLanka\Providers\Hutch;

use Illuminate\Support\Facades\Config;
use Sureshhemal\SmsSriLanka\Contracts\SmsAuthenticatorContract;
use Sureshhemal\SmsSriLanka\Contracts\SmsConfigurationValidator;
use Sureshhemal\SmsSriLanka\Contracts\SmsHttpClient;
use Sureshhemal\SmsSriLanka\Contracts\SmsPayloadBuilder;
use Sureshhemal\SmsSriLanka\Contracts\SmsServiceContract;

class HutchSmsService implements SmsServiceContract
{
    private SmsAuthenticatorContract $authenticator;

    private SmsConfigurationValidator $validator;

    private SmsPayloadBuilder $payloadBuilder;

    private SmsHttpClient $httpClient;

    private string $baseUrl;

    public function __construct(
        SmsAuthenticatorContract $authenticator,
        SmsConfigurationValidator $validator,
        SmsPayloadBuilder $payloadBuilder,
        SmsHttpClient $httpClient,
    ) {
        $this->authenticator = $authenticator;
        $this->validator = $validator;
        $this->payloadBuilder = $payloadBuilder;
        $this->httpClient = $httpClient;
        $this->baseUrl = Config::get('sms-sri-lanka.providers.hutch.config.base_url');
    }

    /**
     * Send an SMS message
     *
     * @param  string  $to  The recipient phone number(s). Can be a single number or comma-separated list.
     *                      Examples: '94701234567' or '94701234567,94712345678,94723456789'
     * @param  string  $message  The SMS message content
     * @param  array  $options  Additional options for the SMS
     * @return array The response from the SMS API
     */
    public function sendSms(string $to, string $message, array $options = []): array
    {
        // Get configuration
        $config = $this->getConfiguration();

        // Validate configuration and options
        $this->validator->validate($options, $config);

        // Ensure we have an access token
        $accessToken = $this->ensureAccessToken();

        // Build payload
        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        // Send SMS
        return $this->sendSmsRequest($payload, $accessToken, $to, $message, $options);
    }

    /**
     * Get configuration for the service
     */
    private function getConfiguration(): array
    {
        return [
            'default_options' => Config::get('sms-sri-lanka.providers.hutch.default_options', []),
            'default_mask' => Config::get('sms-sri-lanka.providers.hutch.config.default_mask'),
        ];
    }

    /**
     * Ensure we have a valid access token
     */
    private function ensureAccessToken(): string
    {
        $accessToken = $this->authenticator->getAccessToken();
        if (! $accessToken) {
            $this->authenticator->authenticate();
            $accessToken = $this->authenticator->getAccessToken();
        }

        return $accessToken;
    }

    /**
     * Send SMS request with retry logic
     */
    private function sendSmsRequest(array $payload, string $accessToken, string $to, string $message, array $options): array
    {
        $headers = $this->buildHeaders($accessToken);
        $url = "{$this->baseUrl}/sendsms";

        $response = $this->httpClient->post($url, $payload, $headers);

        // If token expired, refresh and try again
        if ($this->httpClient->isAuthenticationFailure($response['status'] ?? 200)) {
            $this->authenticator->refreshToken();

            return $this->sendSms($to, $message, $options);
        }

        return $response;
    }

    /**
     * Build HTTP headers for the request
     */
    private function buildHeaders(string $accessToken): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'X-API-VERSION' => 'v1',
            'Authorization' => "Bearer {$accessToken}",
        ];
    }
}
