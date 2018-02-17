<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Adrenth\Security\Classes\TwoFactorAuthentication\SecretKey;
use Adrenth\Security\Controllers\TwoFactor;
use Adrenth\Security\Plugin;
use Backend;
use Backend\Classes\Controller;
use BackendAuth;
use Hash;
use Session;

/**
 * Class BeforeRoute
 *
 * @package Adrenth\Security\Classes\EventListeners\Backend
 */
class PageBeforeDisplay
{
    /**
     * @param Controller $controller
     */
    public function handle(Controller $controller)
    {
        if ($controller instanceof TwoFactor) {
            return;
        }

        if (!BackendAuth::check()) {
            Session::forget(Plugin::SESSION_KEY);
            return;
        }

        /** @var Backend\Models\User $user */
        $user = BackendAuth::getUser();

        /** @var SecretKey $secretKey */
        $secretKey = resolve(SecretKey::class);
        $secret = $secretKey->decrypt((string) $user->getAttribute('google2fa_secret'));
        if ($secret === '') {
            return;
        }

        if (Session::has(Plugin::SESSION_KEY)) {
            if (!Hash::check($secret, Session::get(Plugin::SESSION_KEY))) {
                Session::forget(Plugin::SESSION_KEY);
            } else {
                return;
            }
        }

        Backend::redirect('adrenth/security/twofactor/verify')->send();
    }
}
