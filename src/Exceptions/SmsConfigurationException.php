<?php

namespace Sureshhemal\SmsSriLanka\Exceptions;

/**
 * Exception thrown when SMS configuration is invalid or missing.
 */
class SmsConfigurationException extends SmsException
{
    /**
     * Create a new SMS configuration exception instance.
     */
    public function __construct(string $message = 'SMS configuration error', int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create an exception for missing required configuration.
     */
    public static function missingConfiguration(string $configKey, string $provider): static
    {
        return new static("Missing required configuration '{$configKey}' for SMS provider '{$provider}'. Please check your configuration.");
    }

    /**
     * Create an exception for invalid configuration value.
     */
    public static function invalidConfiguration(string $configKey, string $provider, string $expectedType): static
    {
        return new static("Invalid configuration '{$configKey}' for SMS provider '{$provider}'. Expected {$expectedType}.");
    }
}
