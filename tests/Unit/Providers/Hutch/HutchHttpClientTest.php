<?php

namespace Tests\Unit\Providers\Hutch;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchHttpClient;

class HutchHttpClientTest extends TestCase
{
    private HutchHttpClient $httpClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClient = new HutchHttpClient;
    }

    #[Test]
    public function sends_post_request_successfully()
    {
        Http::fake([
            'test-url-1' => Http::response(['status' => 'success'], 200),
        ]);

        $url = 'test-url-1';
        $payload = ['test' => 'data'];
        $headers = ['Content-Type' => 'application/json'];

        $response = $this->httpClient->post($url, $payload, $headers);

        $this->assertEquals(['status' => 'success'], $response);
    }

    #[Test]
    public function returns_json_response()
    {
        Http::fake([
            'test-url-2' => Http::response(['message' => 'SMS sent', 'status' => 200], 200),
        ]);

        $url = 'test-url-2';
        $payload = ['campaignName' => 'Test', 'numbers' => '94701234567'];
        $headers = ['Authorization' => 'Bearer token'];

        $response = $this->httpClient->post($url, $payload, $headers);

        $this->assertEquals(['message' => 'SMS sent', 'status' => 200], $response);
    }

    #[Test]
    public function identifies_authentication_failure_correctly()
    {
        $this->assertTrue($this->httpClient->isAuthenticationFailure(401));
        $this->assertFalse($this->httpClient->isAuthenticationFailure(200));
        $this->assertFalse($this->httpClient->isAuthenticationFailure(400));
        $this->assertFalse($this->httpClient->isAuthenticationFailure(500));
    }
}
