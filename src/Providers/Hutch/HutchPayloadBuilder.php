<?php

namespace Sureshhemal\SmsSriLanka\Providers\Hutch;

use Sureshhemal\SmsSriLanka\Contracts\SmsPayloadBuilder;

class HutchPayloadBuilder implements SmsPayloadBuilder
{
    /**
     * Build SMS payload from options
     *
     * @param  string  $to  Recipient phone number(s)
     * @param  string  $message  SMS message content
     * @param  array  $options  SMS options
     * @param  array  $config  Provider configuration
     * @return array The prepared payload
     */
    public function build(string $to, string $message, array $options, array $config = []): array
    {
        $defaultOptions = $config['default_options'] ?? [];

        // Determine campaign name
        $campaignName = $options['campaign_name'] ?? $options['campaignName'] ?? $defaultOptions['campaign_name'];

        // Determine mask
        $mask = $options['mask'] ?? $config['default_mask'];

        // Determine delivery report request
        $deliveryReportRequest = $options['delivery_report_request'] ?? $options['deliveryReportRequest'] ?? $defaultOptions['delivery_report_request'] ?? false;

        return [
            'campaignName' => $campaignName,
            'mask' => $mask,
            'numbers' => $to,
            'content' => $message,
            'deliveryReportRequest' => $deliveryReportRequest,
        ];
    }
}
