<?php

namespace Sureshhemal\SmsSriLanka\Providers\Hutch;

use Sureshhemal\SmsSriLanka\Contracts\SmsConfigurationValidator;
use Sureshhemal\SmsSriLanka\Exceptions\SmsConfigurationException;

class HutchConfigurationValidator implements SmsConfigurationValidator
{
    /**
     * Validate SMS configuration and options
     *
     * @param  array  $options  SMS options to validate
     * @param  array  $config  Provider configuration
     *
     * @throws SmsConfigurationException When validation fails
     */
    public function validate(array $options, array $config = []): void
    {
        $defaultOptions = $config['default_options'] ?? [];

        // Determine campaign name
        $campaignName = $options['campaign_name'] ?? $options['campaignName'] ?? $defaultOptions['campaign_name'] ?? null;

        // Validate campaign name is present
        if (empty($campaignName)) {
            throw SmsConfigurationException::missingConfiguration('campaign_name', 'hutch');
        }

        // Determine mask
        $mask = $options['mask'] ?? $config['default_mask'] ?? null;

        // Validate mask is present
        if (empty($mask)) {
            throw SmsConfigurationException::missingConfiguration('mask', 'hutch');
        }
    }
}
