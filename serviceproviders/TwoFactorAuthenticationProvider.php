<?php

declare(strict_types=1);

namespace Adrenth\Security\ServiceProviders;

use Adrenth\Security\Classes\TwoFactorAuthentication\GoogleTwoFactorAuthentication;
use Adrenth\Security\Classes\TwoFactorAuthentication\SecretKey;
use Adrenth\Security\Classes\TwoFactorAuthentication\TwoFactorAuthentication;
use Illuminate\Contracts\Encryption\Encrypter;
use October\Rain\Support\ServiceProvider;
use PragmaRX\Google2FA\Google2FA;

/**
 * Class TwoFactorAuthenticationProvider
 *
 * @package Adrenth\Security\ServiceProviders
 */
class TwoFactorAuthenticationProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(GoogleTwoFactorAuthentication::class, function () {
            return new GoogleTwoFactorAuthentication(new Google2FA());
        });

        $this->app->bind(SecretKey::class, function () {
            return new SecretKey(resolve(Encrypter::class));
        });

        $this->app->alias(GoogleTwoFactorAuthentication::class, TwoFactorAuthentication::class);
    }
}
