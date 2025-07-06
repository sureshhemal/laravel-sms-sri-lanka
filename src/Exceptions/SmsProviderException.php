<?php

namespace Sureshhemal\SmsSriLanka\Exceptions;

/**
 * Exception thrown when SMS provider is not supported or misconfigured.
 */
class SmsProviderException extends SmsException
{
    /**
     * Create a new SMS provider exception instance.
     */
    public function __construct(string $message = 'SMS provider error', int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create an exception for unsupported provider.
     */
    public static function unsupportedProvider(string $provider): static
    {
        return new static("SMS provider '{$provider}' is not supported.");
    }

    /**
     * Create an exception for missing provider configuration.
     */
    public static function missingProviderConfiguration(string $provider): static
    {
        return new static("SMS provider '{$provider}' configuration is missing. Please check your config file.");
    }

    /**
     * Create an exception for missing provider service class.
     */
    public static function missingProviderService(string $provider): static
    {
        return new static("SMS provider '{$provider}' service class is not configured.");
    }

    /**
     * Create an exception for missing provider authenticator class.
     */
    public static function missingProviderAuthenticator(string $provider): static
    {
        return new static("SMS provider '{$provider}' authenticator class is not configured.");
    }
}
