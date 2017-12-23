<?php

declare(strict_types=1);

namespace Adrenth\Security\Classes\EventListeners\Backend;

use Backend;

/**
 * Class UserLogin
 *
 * @package Adrenth\Security\Classes\EventListeners\Backend
 */
class UserLogin
{
    public function handle()
    {
        Backend::redirect('adrenth/security/twofactor/verify')->send();
    }
}
