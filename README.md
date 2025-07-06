# Laravel SMS Sri Lanka

A Laravel package for sending SMS through Sri Lankan providers with seamless Laravel notifications integration, built with SOLID principles and clean architecture.

## Features

- ✅ **Sri Lankan SMS Provider**: Hutch (more providers coming soon)
- ✅ **Laravel Notifications**: Seamless integration with Laravel's notification system
- ✅ **SOLID Architecture**: Clean, maintainable, and extensible code
- ✅ **Provider Agnostic**: Easy to switch between providers
- ✅ **Configuration Driven**: Simple configuration management
- ✅ **Type Safe**: Full PHP 8.1+ type safety
- ✅ **Bulk SMS Support**: Send to multiple recipients efficiently
- ✅ **Custom Exceptions**: Granular error handling for better debugging
- ✅ **Modern Testing**: Comprehensive test suite with PHPUnit 10+ attributes

## Installation

You can install the package via composer:

```bash
composer require sureshhemal/laravel-sms-sri-lanka
```

The package will automatically register its service provider.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Sureshhemal\SmsSriLanka\Providers\SmsServiceProvider"
```

This will create `config/sms-sri-lanka.php` in your config folder.

## Environment Variables

Add these to your `.env` file:

```env
# SMS Provider
SMS_PROVIDER=hutch

# Hutch SMS Configuration
HUTCH_SMS_BASE_URL=https://bsms.hutch.lk/api
HUTCH_SMS_USERNAME=your_api_username
HUTCH_SMS_PASSWORD=your_api_password
HUTCH_SMS_DEFAULT_MASK=YourApp

# Hutch SMS Default Options
HUTCH_SMS_DELIVERY_REPORT_REQUEST=true
HUTCH_SMS_DEFAULT_CAMPAIGN_NAME=Laravel SMS
```

**Important**: 
- The `HUTCH_SMS_USERNAME` must be an **API user account**, not a regular web user account. If you don't have API access, you must contact your Hutch agent to gain API access to your account.
- The `HUTCH_SMS_DEFAULT_MASK` refers to the "From" field in the Hutch SMS UI dropdown. You must contact your Hutch agent to get approved masks for your account.

## Architecture Overview

The package follows SOLID principles and clean architecture:

```
src/
├── Contracts/                    # Interface definitions
│   ├── SmsServiceContract.php
│   ├── SmsAuthenticatorContract.php
│   ├── SmsConfigurationValidator.php
│   ├── SmsPayloadBuilder.php
│   ├── SmsHttpClient.php
│   ├── ReceivesSmsNotifications.php
│   └── SendsSmsNotification.php
├── Exceptions/                   # Custom exception classes
│   ├── SmsException.php
│   ├── SmsConfigurationException.php
│   ├── SmsSendException.php
│   └── SmsProviderException.php
├── Notifications/                # Laravel notification integration
│   ├── SmsNotification.php
│   └── Channels/SmsChannel.php
└── Providers/                    # Provider implementations
    ├── SmsServiceProvider.php
    └── Hutch/
        ├── HutchSmsService.php
        ├── HutchSmsAuthenticator.php
        ├── HutchConfigurationValidator.php
        ├── HutchPayloadBuilder.php
        └── HutchHttpClient.php
```

## Usage

### 1. Setting Up Your Models

Your models that can receive SMS notifications must implement the `ReceivesSmsNotifications` contract:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Sureshhemal\SmsSriLanka\Contracts\ReceivesSmsNotifications;

class User extends Authenticatable implements ReceivesSmsNotifications
{
    // ... your model code ...

    /**
     * Route notifications for the SMS channel.
     * This method should return the phone number for SMS delivery.
     */
    public function routeNotificationForSms(): ?string
    {
        return $this->phone_number; // or $this->telephone_number, etc.
    }
}
```

### 2. Using Laravel Notifications

#### Using Package's SmsNotification (Quick Start)

```php
use Sureshhemal\SmsSriLanka\Notifications\SmsNotification;

// Send to single recipient
$user->notify(new SmsNotification('Your message here'));

// Send to multiple recipients
Notification::send($users, new SmsNotification('Your message here'));

// With custom options
$notification = new SmsNotification('Your message', [
    'delivery_report_request' => true,
    'campaign_name' => 'Custom Campaign'
]);
$user->notify($notification);
```

#### Creating Custom SMS Notifications

For more complex scenarios, create your own notification classes:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Sureshhemal\SmsSriLanka\Contracts\SendsSmsNotification;

class WelcomeSmsNotification extends Notification implements SendsSmsNotification, ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['sms'];
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable): string
    {
        return "Welcome {$notifiable->name}! Your account has been created successfully.";
    }

    /**
     * Get the SMS options for the notification.
     */
    public function smsOptions(): array
    {
        return [
            'delivery_report_request' => true,
            'campaign_name' => 'Welcome Campaign',
        ];
    }
}
```

### 3. Direct SMS Service Usage

```php
use Sureshhemal\SmsSriLanka\Contracts\SmsServiceContract;

// Inject the service
public function sendWelcomeSms(SmsServiceContract $smsService)
{
    // Send to single recipient
    $response = $smsService->sendSms(
        to: '94701234567',
        message: 'Welcome to our service!',
        options: [
            'campaign_name' => 'Welcome Campaign',
            'delivery_report_request' => true,
        ]
    );

    // Send to multiple recipients (comma-separated phone numbers)
    $response = $smsService->sendSms(
        to: '94701234567,94712345678,94723456789',
        message: 'Bulk message to multiple recipients!',
        options: [
            'campaign_name' => 'Bulk Campaign',
            'delivery_report_request' => true,
        ]
    );

    return $response;
}
```

**Note**: The `to` parameter accepts:
- **Single phone number**: `'94701234567'`
- **Multiple phone numbers**: `'94701234567,94712345678,94723456789'` (comma-separated)

### 4. Error Handling

The package provides granular exception handling:

```php
use Sureshhemal\SmsSriLanka\Exceptions\SmsConfigurationException;
use Sureshhemal\SmsSriLanka\Exceptions\SmsSendException;
use Sureshhemal\SmsSriLanka\Exceptions\SmsProviderException;

try {
    $smsService->sendSms('94701234567', 'Test message');
} catch (SmsConfigurationException $e) {
    // Handle configuration errors (missing API keys, etc.)
    Log::error('SMS Configuration Error: ' . $e->getMessage());
} catch (SmsSendException $e) {
    // Handle sending errors (authentication, API errors, etc.)
    Log::error('SMS Send Error: ' . $e->getMessage());
} catch (SmsProviderException $e) {
    // Handle provider-related errors
    Log::error('SMS Provider Error: ' . $e->getMessage());
}
```

## Contracts

### ReceivesSmsNotifications

Models that can receive SMS notifications must implement this contract:

```php
interface ReceivesSmsNotifications
{
    /**
     * Route notifications for the SMS channel.
     * Return the phone number for SMS delivery.
     */
    public function routeNotificationForSms(): ?string;
}
```

### SendsSmsNotification

Custom notification classes should implement this contract:

```php
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
```

### SmsServiceContract

For direct SMS service usage:

```php
interface SmsServiceContract
{
    /**
     * Send an SMS message
     * 
     * @param string $to Phone number(s) - single number or comma-separated list
     * @param string $message The SMS message content
     * @param array $options Additional options for the SMS
     * @return array The response from the SMS API
     */
    public function sendSms(string $to, string $message, array $options = []): array;
}
```

## Configuration

The package configuration is in `config/sms-sri-lanka.php`:

```php
return [
    'default' => env('SMS_PROVIDER', 'hutch'),
    
    'providers' => [
        'hutch' => [
            'service' => \Sureshhemal\SmsSriLanka\Providers\Hutch\HutchSmsService::class,
            'authenticator' => \Sureshhemal\SmsSriLanka\Providers\Hutch\HutchSmsAuthenticator::class,
            'config' => [
                'base_url' => env('HUTCH_SMS_BASE_URL', 'https://bsms.hutch.lk/api'),
                'username' => env('HUTCH_SMS_USERNAME'),
                'password' => env('HUTCH_SMS_PASSWORD'),
                'default_mask' => env('HUTCH_SMS_DEFAULT_MASK'),
            ],
            'default_options' => [
                'delivery_report_request' => env('HUTCH_SMS_DELIVERY_REPORT_REQUEST', false),
                'campaign_name' => env('HUTCH_SMS_DEFAULT_CAMPAIGN_NAME', 'Laravel SMS'),
            ],
        ],
        // More providers coming soon...
    ],
];
```

## Adding New Providers

The package is designed to be easily extensible. To add a new provider:

1. **Create provider-specific classes** implementing the contracts:
   ```php
   // src/Providers/NewProvider/
   - NewProviderSmsService.php
   - NewProviderSmsAuthenticator.php
   - NewProviderConfigurationValidator.php
   - NewProviderPayloadBuilder.php
   - NewProviderHttpClient.php
   ```

2. **Register in configuration**:
   ```php
   'providers' => [
       'new-provider' => [
           'service' => \Sureshhemal\SmsSriLanka\Providers\NewProvider\NewProviderSmsService::class,
           'authenticator' => \Sureshhemal\SmsSriLanka\Providers\NewProvider\NewProviderSmsAuthenticator::class,
           // ... configuration
       ],
   ]
   ```

3. **Update environment variables**:
   ```env
   SMS_PROVIDER=new-provider
   ```

## Supported Providers

### Hutch
- **Status**: ✅ Available
- **API**: REST API with OAuth2 authentication
- **Features**: Delivery reports, campaign tracking

### More Providers Coming Soon
- **Mobitel**: Government integration
- **Dialog**: Enterprise solutions
- **Etisalat**: International connectivity

## Testing

```bash
composer test
```

The package includes comprehensive tests with modern PHPUnit 10+ attributes:

```php
#[Test]
public function validatesSuccessfullyWithValidConfiguration()
{
    // Test implementation
}
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email sureshhemal@gmail.com instead of using the issue tracker.

## Credits

- [Suresh Hemal](https://github.com/sureshhemal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 