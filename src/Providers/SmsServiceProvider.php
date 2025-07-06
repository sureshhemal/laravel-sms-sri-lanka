<?php

namespace Sureshhemal\SmsSriLanka\Providers;

use Illuminate\Support\ServiceProvider;
use Sureshhemal\SmsSriLanka\Contracts\SmsAuthenticatorContract;
use Sureshhemal\SmsSriLanka\Contracts\SmsConfigurationValidator;
use Sureshhemal\SmsSriLanka\Contracts\SmsHttpClient;
use Sureshhemal\SmsSriLanka\Contracts\SmsPayloadBuilder;
use Sureshhemal\SmsSriLanka\Contracts\SmsServiceContract;
use Sureshhemal\SmsSriLanka\Exceptions\SmsConfigurationException;
use Sureshhemal\SmsSriLanka\Exceptions\SmsProviderException;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchConfigurationValidator;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchHttpClient;
use Sureshhemal\SmsSriLanka\Providers\Hutch\HutchPayloadBuilder;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/sms-sri-lanka.php' => config_path('sms-sri-lanka.php'),
        ], 'config');

        $this->registerConfigurationValidator();
        $this->registerPayloadBuilder();
        $this->registerHttpClient();
        $this->registerAuthenticator();
        $this->registerSmsService();
    }

    /**
     * Register configuration validator
     */
    private function registerConfigurationValidator(): void
    {
        $this->app->bind(SmsConfigurationValidator::class, function ($app) {
            $defaultProvider = config('sms-sri-lanka.default', 'hutch');
            $providerConfig = config("sms-sri-lanka.providers.{$defaultProvider}");

            if (! $providerConfig) {
                throw SmsProviderException::missingProviderConfiguration($defaultProvider);
            }

            return match ($defaultProvider) {
                'hutch' => new HutchConfigurationValidator,
                default => throw SmsProviderException::unsupportedProvider($defaultProvider),
            };
        });
    }

    /**
     * Register payload builder
     */
    private function registerPayloadBuilder(): void
    {
        $this->app->bind(SmsPayloadBuilder::class, function ($app) {
            $defaultProvider = config('sms-sri-lanka.default', 'hutch');
            return match ($defaultProvider) {
                'hutch' => new HutchPayloadBuilder,
                default => throw SmsProviderException::unsupportedProvider($defaultProvider),
            };
        });
    }

    /**
     * Register HTTP client
     */
    private function registerHttpClient(): void
    {
        $this->app->bind(SmsHttpClient::class, function ($app) {
            $defaultProvider = config('sms-sri-lanka.default', 'hutch');
            return match ($defaultProvider) {
                'hutch' => new HutchHttpClient,
                default => throw SmsProviderException::unsupportedProvider($defaultProvider),
            };
        });
    }

    /**
     * Register authenticator
     */
    private function registerAuthenticator(): void
    {
        $this->app->bind(SmsAuthenticatorContract::class, function ($app) {
            $defaultProvider = config('sms-sri-lanka.default', 'hutch');
            $providerConfig = config("sms-sri-lanka.providers.{$defaultProvider}");

            if (! isset($providerConfig['authenticator'])) {
                throw SmsProviderException::missingProviderAuthenticator($defaultProvider);
            }
            if (! isset($providerConfig['config'])) {
                throw SmsProviderException::missingProviderConfiguration($defaultProvider);
            }
            $authenticatorClass = $providerConfig['authenticator'];
            $config = $providerConfig['config'];

            if (! isset($config['username'])) {
                throw SmsConfigurationException::missingConfiguration('username', 'sms provider');
            }
            if (! isset($config['password'])) {
                throw SmsConfigurationException::missingConfiguration('password', 'sms provider');
            }

            return new $authenticatorClass(
                username: $config['username'],
                password: $config['password']
            );
        });
    }

    /**
     * Register SMS service
     */
    private function registerSmsService(): void
    {
        $this->app->bind(SmsServiceContract::class, function ($app) {
            $defaultProvider = config('sms-sri-lanka.default', 'hutch');
            $providerConfig = config("sms-sri-lanka.providers.{$defaultProvider}");

            if (! isset($providerConfig['service'])) {
                throw SmsProviderException::missingProviderService($defaultProvider);
            }
            $serviceClass = $providerConfig['service'];

            return new $serviceClass(
                authenticator: $app->make(SmsAuthenticatorContract::class),
                validator: $app->make(SmsConfigurationValidator::class),
                payloadBuilder: $app->make(SmsPayloadBuilder::class),
                httpClient: $app->make(SmsHttpClient::class),
            );
        });
    }
} 