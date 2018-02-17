<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\TwoFactorAuthentication;

use PragmaRX\Google2FA\Google2FA;

/**
 * Class GoogleTwoFactorAuthentication
 *
 * @package Adrenth\Security\Classes\TwoFactorAuthentication
 */
class GoogleTwoFactorAuthentication implements TwoFactorAuthentication
{
    /**
     * @var Google2FA
     */
    private $google2FA;

    /**
     * @param Google2FA $google2FA
     */
    public function __construct(Google2FA $google2FA)
    {
        $this->google2FA = $google2FA;
    }

    /**
     * {@inheritdoc}
     */
    public function verifyKey(string $secret, string $key): bool
    {
        return $this->google2FA->verifyKey($secret, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function generateSecretKey(int $length, string $prefix): string
    {
        return $this->google2FA->generateSecretKey($length, $prefix);
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * {@inheritdoc}
     */
    public function getQRCodeUrl(string $company, string $holder, string $secretKey, int $size): string
    {
        return $this->google2FA->getQRCodeGoogleUrl(
            $company,
            $holder,
            $secretKey,
            $size
        );
    }
}
