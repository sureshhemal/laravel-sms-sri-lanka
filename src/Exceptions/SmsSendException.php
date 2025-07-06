<?php

namespace Sureshhemal\SmsSriLanka\Exceptions;

/**
 * Exception thrown when SMS sending fails.
 */
class SmsSendException extends SmsException
{
    /**
     * Create a new SMS send exception instance.
     */
    public function __construct(string $message = 'SMS sending failed', int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create an exception for authentication failure.
     */
    public static function authenticationFailed(string $provider): static
    {
        return new static("Authentication failed for SMS provider '{$provider}'. Please check your credentials.");
    }

    /**
     * Create an exception for API error.
     */
    public static function apiError(string $provider, string $errorMessage, int $statusCode): static
    {
        return new static("SMS API error for provider '{$provider}': {$errorMessage} (Status: {$statusCode})");
    }

    /**
     * Create an exception for invalid phone number.
     */
    public static function invalidPhoneNumber(string $phoneNumber): static
    {
        return new static("Invalid phone number format: {$phoneNumber}");
    }

    /**
     * Create an exception for empty message.
     */
    public static function emptyMessage(): static
    {
        return new static('SMS message cannot be empty.');
    }
}
