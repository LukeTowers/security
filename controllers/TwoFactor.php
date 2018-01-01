<?php

declare(strict_types=1);

namespace Adrenth\Security\Controllers;

use Adrenth\Security\Plugin;
use Backend;
use Backend\Classes\Controller;
use BackendAuth;
use Flash;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Input;
use PragmaRX\Google2FA\Google2FA;
use RuntimeException;
use Session;

/**
 * Class TwoFactorController
 *
 * @package Adrenth\Security
 */
class TwoFactor extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->layout = 'auth';
        $this->viewPath = 'plugins/adrenth/security/controllers/twofactor';
    }

    /**
     * adrenth/security/twofactor/verify
     *
     * @return RedirectResponse|Redirector
     */
    public function verify()
    {
        if (!BackendAuth::check()) {
            return redirect(Backend::url('/'));
        }
    }

    // @codingStandardsIgnoreStart

    /**
     * Verification of the Authentication code.
     *
     * @return RedirectResponse|Redirector
     * @throws RuntimeException
     */
    public function verify_onVerify()
    {
        if (!BackendAuth::check()) {
            return redirect(Backend::url('/'));
        }

        /** @var Backend\Models\User $user */
        $user = BackendAuth::getUser();
        $userSecret = $user->getAttribute('google2fa_secret');

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($userSecret, Input::get('key'))) {
            Session::put(Plugin::SESSION_KEY, Hash::make($userSecret));
            return redirect(Backend::url('/'));
        }

        Flash::error(trans('adrenth.security::lang.2fa.invalid_authentication_code'));
    }

    // @codingStandardsIgnoreEnd
}
