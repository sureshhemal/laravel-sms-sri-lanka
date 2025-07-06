<?php

namespace Sureshhemal\SmsSriLanka\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Sureshhemal\SmsSriLanka\Contracts\ReceivesSmsNotifications;
use Sureshhemal\SmsSriLanka\Contracts\SendsSmsNotification;
use Sureshhemal\SmsSriLanka\Contracts\SmsServiceContract;

class SmsChannel
{
    protected SmsServiceContract $smsService;

    public function __construct(SmsServiceContract $smsService)
    {
        $this->smsService = $smsService;
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $this->isValidNotification($notification)) {
            return;
        }

        $message = $notification->toSms($notifiable);
        if (empty($message)) {
            return;
        }

        $phoneNumber = $this->getPhoneNumber($notifiable);
        if (empty($phoneNumber)) {
            return;
        }

        $this->sendSms($phoneNumber, $message, $notification);
    }

    /**
     * Check if the notification is valid for SMS sending.
     */
    private function isValidNotification($notification): bool
    {
        return $notification instanceof SendsSmsNotification;
    }

    /**
     * Send SMS using the SMS service.
     */
    private function sendSms($phoneNumber, $message, $notification): void
    {
        $options = $notification instanceof SendsSmsNotification ? $notification->smsOptions() : [];

        $this->smsService->sendSms(
            to: $phoneNumber,
            message: $message,
            options: $options
        );
    }

    protected function getPhoneNumber($notifiable): ?string
    {
        if ($notifiable instanceof ReceivesSmsNotifications) {
            return $notifiable->routeNotificationForSms();
        }

        return null;
    }
}
