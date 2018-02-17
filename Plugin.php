<?php

declare(strict_types=1);

namespace Adrenth\Security;

use Adrenth\Security\Classes\EventSubscribers\BackendEventSubscriber;
use Adrenth\Security\Classes\TwoFactorAuthentication\SecretKey;
use Adrenth\Security\Classes\TwoFactorAuthentication\TwoFactorAuthentication;
use Adrenth\Security\ServiceProviders\TwoFactorAuthenticationProvider;
use Backend\Controllers\Users;
use Backend\Models\User;
use BackendAuth;
use Event;
use Input;
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
            'name' => 'adrenth.security::lang.plugin.name',
            'description' => 'adrenth.security::lang.plugin.description',
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
        $this->registerServiceProviders();

        Users::extend(function (Users $controller) {
            $controller->addViewPath(plugins_path('adrenth/security/controllers/users'));

            $controller->addDynamicMethod('onSetupTwoFactorAuthenticationPopup', function () use ($controller) {
                return $controller->makePartial('2fa_popup', ['user' => BackendAuth::getUser()]);
            });

            $controller->addDynamicMethod('onSaveTwoFactorAuthentication', function () use ($controller) {
                /** @var User $user */
                $user = BackendAuth::getUser();

                /** @var SecretKey $secretKey */
                $secretKey = resolve(SecretKey::class);
                $currentSecret = $secretKey->decrypt((string) $user->getAttribute('google2fa_secret'));

                /** @var TwoFactorAuthentication $twoFactorAuthentication */
                $twoFactorAuthentication = resolve(TwoFactorAuthentication::class);
                if ($currentSecret !== '' && !$twoFactorAuthentication->verifyKey($currentSecret, Input::get('key'))) {
                    throw new ValidationException(['secret' => trans('adrenth.security::lang.2fa.invalid_secret')]);
                }

                $user->setAttribute('google2fa_secret', $secretKey->encrypt(Input::get('secret')));
                $user->save();

                return [
                    '#Form-field-User-2fa_setup_button-group' => $controller->makePartial('2fa_setup_button')
                ];
            });
        });
    }

    /**
     * @return void
     */
    private function registerServiceProviders(): void
    {
        $this->app->register(TwoFactorAuthenticationProvider::class);
    }
}
