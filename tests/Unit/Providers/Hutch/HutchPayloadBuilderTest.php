<?php

namespace Tests\Unit\Providers\Hutch;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchPayloadBuilder;

class HutchPayloadBuilderTest extends TestCase
{
    private HutchPayloadBuilder $payloadBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payloadBuilder = new HutchPayloadBuilder;
    }

    #[Test]
    public function builds_payload_with_basic_parameters()
    {
        $to = '94701234567';
        $message = 'Test message';
        $options = [
            'campaign_name' => 'TestCampaign',
            'mask' => 'TestMask',
        ];

        $config = [];

        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        $this->assertEquals([
            'campaignName' => 'TestCampaign',
            'mask' => 'TestMask',
            'numbers' => '94701234567',
            'content' => 'Test message',
            'deliveryReportRequest' => false,
        ], $payload);
    }

    #[Test]
    public function builds_payload_with_default_options_from_config()
    {
        $to = '94701234567';
        $message = 'Test message';
        $options = [];

        $config = [
            'default_options' => [
                'campaign_name' => 'DefaultCampaign',
                'delivery_report_request' => true,
            ],
            'default_mask' => 'DefaultMask',
        ];

        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        $this->assertEquals([
            'campaignName' => 'DefaultCampaign',
            'mask' => 'DefaultMask',
            'numbers' => '94701234567',
            'content' => 'Test message',
            'deliveryReportRequest' => true,
        ], $payload);
    }

    #[Test]
    public function accepts_campaign_name_as_alternative_to_campaign_name()
    {
        $to = '94701234567';
        $message = 'Test message';
        $options = [
            'campaignName' => 'TestCampaign',
            'mask' => 'TestMask',
        ];

        $config = [];

        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        $this->assertEquals('TestCampaign', $payload['campaignName']);
    }

    #[Test]
    public function accepts_delivery_report_request_as_alternative_to_delivery_report_request()
    {
        $to = '94701234567';
        $message = 'Test message';
        $options = [
            'campaign_name' => 'TestCampaign',
            'mask' => 'TestMask',
            'deliveryReportRequest' => true,
        ];

        $config = [];

        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        $this->assertTrue($payload['deliveryReportRequest']);
    }

    #[Test]
    public function handles_multiple_phone_numbers()
    {
        $to = '94701234567,94712345678,94723456789';
        $message = 'Test message';
        $options = [
            'campaign_name' => 'TestCampaign',
            'mask' => 'TestMask',
        ];

        $config = [];

        $payload = $this->payloadBuilder->build($to, $message, $options, $config);

        $this->assertEquals('94701234567,94712345678,94723456789', $payload['numbers']);
    }
}
