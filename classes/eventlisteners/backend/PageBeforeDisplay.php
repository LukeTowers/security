<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Adrenth\Security\Controllers\TwoFactor;
use Backend;
use Backend\Classes\Controller;
use BackendAuth;
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
            return;
        }

        if (Session::has('adrenth.securiry.2fa')) {
            return;
        }

        Backend::redirect('adrenth/security/twofactor/verify')->send();
    }
}
