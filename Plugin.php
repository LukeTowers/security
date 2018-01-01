<?php

declare(strict_types=1);

namespace Adrenth\Security;

use Adrenth\Security\Classes\EventSubscribers\BackendEventSubscriber;
use Backend\Controllers\Users;
use Backend\Models\User;
use BackendAuth;
use Event;
use Input;
use PragmaRX\Google2FA\Google2FA;
use System\Classes\PluginBase;
use ValidationException;

/**
 * Class Plugin
 *
 * @package Adrenth\Security
 */
class Plugin extends PluginBase
{
    const SESSION_KEY = 'adrenth.security.2fa';

    /**
     * This plugin should have elevated privileges.
     *
     * @var bool
     */
    public $elevated = true;

    /**
     * {@inheritdoc}
     */
    public function pluginDetails(): array
    {
        return [
            'name' => 'Security',
            'description' => 'Improves the security of OctoberCMS',
            'author' => 'Alwin Drenth',
            'icon' => 'icon-link',
            'homepage' => 'http://octobercms.com/plugin/adrenth-security',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        Event::subscribe(BackendEventSubscriber::class);
    }

    /**
     * {@inheritdoc}
     * @throws ValidationException
     */
    public function register()
    {
        Users::extend(function (Users $controller) {
            $controller->addViewPath(plugins_path('adrenth/security/controllers/users'));

            $controller->addDynamicMethod('onSetupTwoFactorAuthenticationPopup', function () use ($controller) {
                return $controller->makePartial('2fa_popup', ['user' => BackendAuth::getUser()]);
            });

            $controller->addDynamicMethod('onSaveTwoFactorAuthentication', function () use ($controller) {
                /** @var User $user */
                $user = BackendAuth::getUser();
                $currentSecret = (string) $user->getAttribute('google2fa_secret');

                if ($currentSecret !== ''
                    && !(new Google2FA())->verifyKey($currentSecret, Input::get('key'))
                ) {
                    throw new ValidationException(['secret' => 'Invalid secret provided. Try again.']);
                }

                $user->setAttribute('google2fa_secret', Input::get('secret'));
                $user->save();

                return [
                    '#Form-field-User-2fa_setup_button-group' => $controller->makePartial('2fa_setup_button')
                ];
            });
        });
    }
}
