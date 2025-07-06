<?php

namespace Sureshhemal\SmsSriLanka\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Sureshhemal\SmsSriLanka\Contracts\SendsSmsNotification;

class SmsNotification extends Notification implements SendsSmsNotification, ShouldQueue
{
    use Queueable;

    protected string $message;

    protected array $options;

    public function __construct(string $message, array $options = [])
    {
        $this->message = $message;
        $this->options = $options;
    }

    public function via($notifiable)
    {
        return ['sms'];
    }

    public function toSms($notifiable): string
    {
        return $this->message;
    }

    public function smsOptions(): array
    {
        $defaultProvider = config('sms-sri-lanka.default', 'hutch');
        $defaultOptions = config("sms-sri-lanka.providers.{$defaultProvider}.default_options", []);

        return array_merge($defaultOptions, $this->options);
    }
}
