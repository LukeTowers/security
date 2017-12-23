<?php

declare(strict_types=1);

namespace Adrenth\Security\Controllers;

use Backend;
use Backend\Classes\Controller;
use BackendAuth;
use Flash;
use Input;
use PragmaRX\Google2FA\Google2FA;
use Session;

/**
 * Class TwoFactorController
 *
 * @package Adrenth\Security
 */
class TwoFactor extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->layout = 'auth';
        $this->viewPath = 'plugins/adrenth/security/controllers/twofactor';
    }

    public function verify()
    {
        if (!BackendAuth::check()) {
            return redirect(Backend::url('/'));
        }
    }

    // @codingStandardsIgnoreStart

    public function verify_onVerify()
    {
        if (!BackendAuth::check()) {
            return redirect(Backend::url('/'));
        }

        $user = BackendAuth::getUser();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->google2fa_secret, Input::get('secret'))) {
            Session::put('adrenth.securiry.2fa', time());
            return redirect(Backend::url('/'));
        }

        Flash::error('Invalid authentication code');
    }

    // @codingStandardsIgnoreEnd
}
