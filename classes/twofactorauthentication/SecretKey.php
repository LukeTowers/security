<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\TwoFactorAuthentication;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;

/**
 * Class EncryptedKey
 *
 * @package Adrenth\Security\Classes\TwoFactorAuthentication
 */
class SecretKey
{
    /**
     * @var Encrypter
     */
    private $encrypter;

    /**
     * @param Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * @param string $secretKey
     * @return string
     */
    public function encrypt(string $secretKey): string
    {
        return $this->encrypter->encrypt($secretKey);
    }

    /**
     * @param string $encryptedSecretKey
     * @return string
     */
    public function decrypt(string $encryptedSecretKey): string
    {
        try {
            return $this->encrypter->decrypt($encryptedSecretKey);
        } catch (DecryptException $e) {
            return '';
        }
    }
}
