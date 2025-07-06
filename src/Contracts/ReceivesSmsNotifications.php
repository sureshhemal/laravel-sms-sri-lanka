<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface ReceivesSmsNotifications
{
    /**
     * Route notifications for the SMS channel.
     */
    public function routeNotificationForSms(): ?string;
}
