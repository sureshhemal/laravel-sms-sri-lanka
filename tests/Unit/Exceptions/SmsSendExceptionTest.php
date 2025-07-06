<?php

namespace Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Exceptions\SmsSendException;

class SmsSendExceptionTest extends TestCase
{
    #[Test]
    public function creates_exception_with_default_message()
    {
        $exception = new SmsSendException;

        $this->assertEquals('SMS sending failed', $exception->getMessage());
    }

    #[Test]
    public function creates_exception_with_custom_message()
    {
        $message = 'Custom send error';
        $exception = new SmsSendException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    #[Test]
    public function creates_authentication_failed_exception()
    {
        $exception = SmsSendException::authenticationFailed('hutch');

        $this->assertEquals(
            'Authentication failed for SMS provider \'hutch\'. Please check your credentials.',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_api_error_exception()
    {
        $exception = SmsSendException::apiError('hutch', 'Invalid phone number', 400);

        $this->assertEquals(
            'SMS API error for provider \'hutch\': Invalid phone number (Status: 400)',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_invalid_phone_number_exception()
    {
        $exception = SmsSendException::invalidPhoneNumber('123');

        $this->assertEquals(
            'Invalid phone number format: 123',
            $exception->getMessage()
        );
    }

    #[Test]
    public function creates_empty_message_exception()
    {
        $exception = SmsSendException::emptyMessage();

        $this->assertEquals(
            'SMS message cannot be empty.',
            $exception->getMessage()
        );
    }
}
