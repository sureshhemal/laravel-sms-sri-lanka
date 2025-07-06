<?php

namespace Tests\Unit\Providers\Hutch;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Exceptions\SmsConfigurationException;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchConfigurationValidator;

class HutchConfigurationValidatorTest extends TestCase
{
    private HutchConfigurationValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new HutchConfigurationValidator;
    }

    #[Test]
    public function validates_successfully_with_valid_configuration()
    {
        $options = [
            'campaign_name' => 'TestCampaign',
            'mask' => 'TestMask',
        ];

        $config = [
            'default_options' => [
                'delivery_report_request' => true,
            ],
        ];

        // Should not throw any exception
        $this->validator->validate($options, $config);
        $this->assertTrue(true);
    }

    #[Test]
    public function throws_exception_when_campaign_name_is_missing()
    {
        $this->expectException(SmsConfigurationException::class);
        $this->expectExceptionMessage('Missing required configuration \'campaign_name\' for SMS provider \'hutch\'. Please check your configuration.');

        $options = [
            'mask' => 'TestMask',
        ];

        $config = [];

        $this->validator->validate($options, $config);
    }

    #[Test]
    public function throws_exception_when_mask_is_missing()
    {
        $this->expectException(SmsConfigurationException::class);
        $this->expectExceptionMessage('Missing required configuration \'mask\' for SMS provider \'hutch\'. Please check your configuration.');

        $options = [
            'campaign_name' => 'TestCampaign',
        ];

        $config = [];

        $this->validator->validate($options, $config);
    }

    #[Test]
    public function uses_default_options_from_config_when_not_provided()
    {
        $options = [];

        $config = [
            'default_options' => [
                'campaign_name' => 'DefaultCampaign',
            ],
            'default_mask' => 'DefaultMask',
        ];

        // Should not throw any exception
        $this->validator->validate($options, $config);
        $this->assertTrue(true);
    }

    #[Test]
    public function accepts_campaign_name_as_alternative_to_campaign_name()
    {
        $options = [
            'campaignName' => 'TestCampaign',
            'mask' => 'TestMask',
        ];

        $config = [];

        // Should not throw any exception
        $this->validator->validate($options, $config);
        $this->assertTrue(true);
    }
}
