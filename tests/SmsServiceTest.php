<?php

namespace Sureshhemal\SmsSriLanka\Tests;

use Illuminate\Notifications\Notifiable;
use PHPUnit\Framework\Attributes\Test;
use Sureshhemal\SmsSriLanka\Contracts\ReceivesSmsNotifications;
use Sureshhemal\SmsSriLanka\Contracts\SmsServiceContract;
use Sureshhemal\SmsSriLanka\Notifications\SmsNotification;

class SmsServiceTest extends TestCase
{
    #[Test]
    public function sms_service_can_be_resolved()
    {
        $smsService = app(SmsServiceContract::class);

        $this->assertInstanceOf(SmsServiceContract::class, $smsService);
    }

    #[Test]
    public function sms_notification_can_be_created()
    {
        $notification = new SmsNotification('Test message');

        $this->assertInstanceOf(SmsNotification::class, $notification);
        $this->assertEquals('Test message', $notification->toSms(null));
    }

    #[Test]
    public function sms_notification_options()
    {
        $notification = new SmsNotification('Test message', [
            'delivery_report_request' => true,
            'campaign_name' => 'Test Campaign',
        ]);

        $options = $notification->smsOptions();

        $this->assertTrue($options['delivery_report_request']);
        $this->assertEquals('Test Campaign', $options['campaign_name']);
    }

    #[Test]
    public function sms_notification_options_merging()
    {
        $notification = new SmsNotification('Test message', [
            'delivery_report_request' => true,
            'campaign_name' => 'Custom Campaign',
        ]);

        $options = $notification->smsOptions();

        // Should merge with config defaults
        $this->assertArrayHasKey('delivery_report_request', $options);
        $this->assertArrayHasKey('campaign_name', $options);
        $this->assertTrue($options['delivery_report_request']);
        $this->assertEquals('Custom Campaign', $options['campaign_name']);
    }

    #[Test]
    public function sms_channel_with_invalid_notification()
    {
        $user = new TestUser('94701234567');

        // Create a notification that doesn't implement SendsSmsNotification
        $invalidNotification = new InvalidNotification;

        $user->notify($invalidNotification);

        // Should not throw exception, just silently fail
        $this->assertTrue(true);
    }

    #[Test]
    public function sms_channel_without_sms_method()
    {
        $user = new TestUser('94701234567');

        // Create a notification that implements SendsSmsNotification but returns empty message
        $notification = new EmptyMessageNotification;

        $user->notify($notification);

        // Should not throw exception, just silently fail
        $this->assertTrue(true);
    }

    #[Test]
    public function sms_channel_without_phone_number_method()
    {
        // Create a user that doesn't implement ReceivesSmsNotifications
        $user = new RegularUser;

        $notification = new SmsNotification('Test message');

        $user->notify($notification);

        // Should not throw exception, just silently fail
        $this->assertTrue(true);
    }

    #[Test]
    public function sms_channel_with_null_phone_number()
    {
        $user = new TestUser;
        $user->setPhoneNumber(''); // Empty phone number

        $notification = new SmsNotification('Test message');

        $user->notify($notification);

        // Should not throw exception, just silently fail
        $this->assertTrue(true);
    }

    #[Test]
    public function sms_notification_via_method()
    {
        $notification = new SmsNotification('Test message');

        $this->assertEquals(['sms'], $notification->via(null));
    }

    #[Test]
    public function sms_notification_implements_contract()
    {
        $notification = new SmsNotification('Test message');

        $this->assertInstanceOf(\Sureshhemal\SmsSriLanka\Contracts\SendsSmsNotification::class, $notification);
    }
}

// Test notifiable class that implements ReceivesSmsNotifications
class TestUser implements ReceivesSmsNotifications
{
    use Notifiable;

    private string $phoneNumber = '';

    public function __construct(string $phoneNumber = '')
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function routeNotificationForSms(): ?string
    {
        return $this->phoneNumber ?: null;
    }
}

// Regular user without SMS notification support
class RegularUser
{
    use Notifiable;
}

// Invalid notification that doesn't implement SendsSmsNotification
class InvalidNotification extends \Illuminate\Notifications\Notification
{
    public function via($notifiable)
    {
        return ['sms'];
    }
}

// Notification that implements SendsSmsNotification but returns empty message
class EmptyMessageNotification extends \Illuminate\Notifications\Notification implements \Sureshhemal\SmsSriLanka\Contracts\SendsSmsNotification
{
    public function via($notifiable)
    {
        return ['sms'];
    }

    public function toSms($notifiable): string
    {
        return ''; // Empty message
    }

    public function smsOptions(): array
    {
        return [];
    }
}
