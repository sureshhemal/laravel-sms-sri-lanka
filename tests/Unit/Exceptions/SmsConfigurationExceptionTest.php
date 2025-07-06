<?php

namespace Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Exceptions\SmsConfigurationException;

class SmsConfigurationExceptionTest extends TestCase
{
    #[Test]
    public function creates_exception_with_default_message()
    {
        $exception = new SmsConfigurationException;

        $this->assertEquals('SMS configuration error', $exception->getMessage());
    }

    #[Test]
    public function creates_exception_with_custom_message()
    {
        $message = 'Custom configuration error';
        $exception = new SmsConfigurationException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    #[Test]
    public function creates_missing_configuration_exception()
    {
        $exception = SmsConfigurationException::missingConfiguration('api_key', 'hutch');

        $this->assertEquals(
            'Missing required configuration \'api_key\' for SMS provider \'hutch\'. Please check your configuration.',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_invalid_configuration_exception()
    {
        $exception = SmsConfigurationException::invalidConfiguration('base_url', 'hutch', 'string');

        $this->assertEquals(
            'Invalid configuration \'base_url\' for SMS provider \'hutch\'. Expected string.',
            $exception->getMessage()
        );
    }
}
