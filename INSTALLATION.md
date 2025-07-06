# Local Installation Guide

This guide helps you test the package locally during development.

## Step 1: Install Dependencies

```bash
composer install
```

## Step 2: Test the Package

```bash
composer test
```

## Step 3: Test in a Laravel Application

### Option A: Using Composer Path Repository

1. In your Laravel application's `composer.json`, add:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/laravel-sms-sri-lanka"
        }
    ]
}
```

2. Install the package:

```bash
composer require sureshhemal/laravel-sms-sri-lanka
```

### Option B: Using Symlink

1. Create a symlink in your Laravel application:

```bash
cd /path/to/your/laravel/app
ln -s /path/to/laravel-sms-sri-lanka vendor/sureshhemal/laravel-sms-sri-lanka
```

2. Add to composer.json:

```json
{
    "require": {
        "sureshhemal/laravel-sms-sri-lanka": "*"
    }
}
```

## Step 4: Publish Configuration

```bash
php artisan vendor:publish --provider="Sureshhemal\SmsSriLanka\Providers\SmsServiceProvider"
```

## Step 5: Configure Environment

Add to your `.env`:

```env
SMS_PROVIDER=hutch
HUTCH_SMS_BASE_URL=https://bsms.hutch.lk/api
HUTCH_SMS_USERNAME=your_username
HUTCH_SMS_PASSWORD=your_password
HUTCH_SMS_DEFAULT_MASK=YourApp
```

## Step 6: Test Usage

```php
use Sureshhemal\SmsSriLanka\Notifications\SmsNotification;

$user->notify(new SmsNotification('Test message'));
```

## Development Workflow

1. Make changes to the package
2. Run tests: `composer test`
3. Test in your Laravel application
4. Commit changes
5. Push to GitHub
6. Submit to Packagist 