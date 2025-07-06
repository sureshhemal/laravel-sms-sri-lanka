<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

use Illuminate\Notifications\Notification;

interface SendsSmsNotification
{
    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string;

    /**
     * Get the SMS options for the notification.
     */
    public function smsOptions(): array;
}
