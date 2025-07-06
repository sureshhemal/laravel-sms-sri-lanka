<?php

namespace Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Exceptions\SmsProviderException;

class SmsProviderExceptionTest extends TestCase
{
    #[Test]
    public function creates_exception_with_default_message()
    {
        $exception = new SmsProviderException;

        $this->assertEquals('SMS provider error', $exception->getMessage());
    }

    #[Test]
    public function creates_exception_with_custom_message()
    {
        $message = 'Custom provider error';
        $exception = new SmsProviderException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    #[Test]
    public function creates_unsupported_provider_exception()
    {
        $exception = SmsProviderException::unsupportedProvider('mobitel');

        $this->assertEquals(
            'SMS provider \'mobitel\' is not supported.',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_missing_provider_configuration_exception()
    {
        $exception = SmsProviderException::missingProviderConfiguration('dialog');

        $this->assertEquals(
            'SMS provider \'dialog\' configuration is missing. Please check your config file.',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_missing_provider_service_exception()
    {
        $exception = SmsProviderException::missingProviderService('hutch');

        $this->assertEquals(
            'SMS provider \'hutch\' service class is not configured.',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_missing_provider_authenticator_exception()
    {
        $exception = SmsProviderException::missingProviderAuthenticator('hutch');

        $this->assertEquals(
            'SMS provider \'hutch\' authenticator class is not configured.',
            $exception->getMessage()
        );
    }
}
