<?php

namespace Sureshhemal\SmsSriLanka\Contracts;

interface SmsConfigurationValidator
{
    /**
     * Validate SMS configuration and options
     *
     * @param  array  $options  SMS options to validate
     * @param  array  $config  Provider configuration
     *
     * @throws \InvalidArgumentException When validation fails
     */
    public function validate(array $options, array $config = []): void;
}
